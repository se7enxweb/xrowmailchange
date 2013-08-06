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
		$http = eZHTTPTool::instance();
		$exclude_siteaccesses = $xrowChangeMailINI->variable( 'GeneralSettings', 'WorkflowExcludeSiteaccess' );
		$siteaccess = eZSiteAccess::current();
		if( !in_array($siteaccess["name"], $exclude_siteaccesses) )
		{
			$cur_user = eZUser::currentUser();
			$contentobject_id = $cur_user->attribute("contentobject_id");
			$user_obj = eZContentObject::fetch($contentobject_id);
			$old_mail = $cur_user->attribute("email");
			$new_mail = $http->postVariable('new_mail');
			
			if( $new_mail !== $old_mail)
			{
				$db = eZDB::instance();
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
				//mail versand
				//opeartor bauen der anzeigt ob unbestätigt ist
				//modul bauen mit template für bestätigung
			}
		}

        return eZWorkflowType::STATUS_ACCEPTED;
    }
}

eZWorkflowEventType::registerEventType( notifyAfterPublishType::WORKFLOW_TYPE_STRING, 'notifyAfterPublishType' );

?>