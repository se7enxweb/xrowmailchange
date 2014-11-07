<?php

class notifyAfterPublishType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = 'notifyafterpublish';
    
    function notifyAfterPublishType()
    {
        $this->eZWorkflowEventType( notifyAfterPublishType::WORKFLOW_TYPE_STRING, ezpI18n::tr( 'extension/xrowmailchange', 'Notify' ) );
        $this->setTriggerTypes( array( 'content' => array( 'publish' => array ( 'before' ) ) ) );
    }
    
    function execute( $process, $event )
    {
        $parameters = $process->attribute( 'parameter_list' );
        $xrowChangeMailINI = eZINI::instance( 'xrowmailchange.ini' );
        $siteINI = eZINI::instance( 'site.ini' );
        $http = eZHTTPTool::instance();
        $exclude_siteaccesses = $xrowChangeMailINI->variable( 'GeneralSettings', 'WorkflowExcludeSiteaccess' );
        $siteaccess = eZSiteAccess::current();
        $tpl = eZTemplate::factory();
        $cur_user = eZUser::currentUser();
        if( isset($parameters["object_id"]) )
        {
            $contentobject_id = $parameters["object_id"];
        }
        elseif( $http->postVariable('mail_change_user_id') AND $http->postVariable('mail_change_user_id') != "" )
        {
            $contentobject_id = $http->postVariable('mail_change_user_id');
        }
        else
        {
            //if this is the case, we have done something wrong. to avoid issues we just let it run through
            eZDebug::writeError( "Notifyafterpublish.php: Could not find the user for the mail change process." );
            return eZWorkflowType::STATUS_ACCEPTED;
        }
        $user = eZuser::fetch($contentobject_id);
        $old_mail = $user->attribute("email");
        $new_mail = $http->postVariable('new_mail');

        if( !eZMail::validate($new_mail) )
        {
            //could be improved for sure
            eZDebug::writeError( "New email is not valid" );
            return eZWorkflowType::STATUS_WORKFLOW_CANCELLED;
        }

        $exclude_usergroups = $xrowChangeMailINI->variable( 'GeneralSettings', 'UserGroupExcludes' );
        $skip_workflow = false;
        
        foreach ( $exclude_usergroups as $usergroup_id )
        {
            if( in_array($usergroup_id, $cur_user->attribute("groups")) )
            {
                $skip_workflow = true;
            }
        }
        
        if( !in_array($siteaccess["name"], $exclude_siteaccesses) AND ($new_mail != "" && $new_mail !== $old_mail) AND $skip_workflow === false )
        {
            $db = eZDB::instance();
            $receiver_type = $xrowChangeMailINI->variable( 'GeneralSettings', 'ConfirmationMailTo' );
            $time = time();
            $hash = md5( mt_rand() . $time . $contentobject_id );
            $check_previous_request = $db->arrayQuery("SELECT * FROM xrow_mailchange WHERE user_id = $contentobject_id;");
            if ( count($check_previous_request) >= 1 )
            {
                $db->begin();
                $db->arrayQuery("DELETE FROM xrow_mailchange WHERE user_id = $contentobject_id;");
                $db->commit();
            }
            $db->begin();
            $db->arrayQuery("INSERT INTO xrow_mailchange ( hash, user_id, new_mail, change_time ) VALUES ( '$hash', $contentobject_id, '$new_mail', $time );");
            $db->commit();

            $tpl->setVariable( 'hostname', eZSys::hostname() );
            $tpl->setVariable( 'hash', $hash );
            $tpl->setVariable( 'new_mail', $new_mail );
            $tpl->setVariable( 'old_mail', $old_mail );

            $templateResult = $tpl->fetch( 'design:mailchange/mail/activation.tpl' );
    
            $mail = new eZMail();
            $mail->setSender( $siteINI->variable( 'MailSettings', 'EmailSender' ) );
            if ( $receiver_type == "oldaddress")
            {
                $mail->setReceiver( $old_mail );
            }
            else
            {
                $mail->setReceiver( $new_mail );
            }
            $mail->setSubject( ezpI18n::tr( 'extension/xrowmailchange', 'Please approve you new email address' ) );
            $mail->setBody( $templateResult );
            $mailResult = eZMailTransport::send( $mail );
        }
        elseif( $new_mail != "" )
        {
            //fallback for cases where the workflow shouldnt run through the mail change process
            $user->setAttribute( "email", "$new_mail" );
            $user->store();
        }
        else
        {
            eZDebug::writeError( "Something went wrong when changing the users mail address(notifyafterpublishtype.php): $new_mail (=new mail), $old_mail (=old mail), $contentobject_id (=user id)" );
        }

        return eZWorkflowType::STATUS_ACCEPTED;
    }
}

eZWorkflowEventType::registerEventType( notifyAfterPublishType::WORKFLOW_TYPE_STRING, 'notifyAfterPublishType' );

?>