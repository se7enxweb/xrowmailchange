<?php

class notifyAfterPublishType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = 'notifyafterpublish';
    
    function notifyAfterPublishType()
    {
        $this->eZWorkflowEventType( notifyAfterPublishType::WORKFLOW_TYPE_STRING, ezpI18n::tr( 'extension/xrowmailchange', 'Notify' ) );
        $this->setTriggerTypes( array( 'content' => array( 'publish' => array ( 'after' ) ) ) );
    }
    
    function execute( $process, $event )
    {
		$xrowChangeMailINI = eZINI::instance( 'xrowmailchange.ini' );
		$exclude_siteaccesses = $xrowChangeMailINI->variable( 'GeneralSettings', 'WorkflowExcludeSiteaccess' );
		$siteaccess = eZSiteAccess::current();
		if( !in_array($siteaccess["name"], $exclude_siteaccesses) )
		{
			$cur_user = eZUser::currentUser();
			$contentobject_id = $cur_user->attribute("contentobject_id");
			$user_obj = eZContentObject::fetch($contentobject_id);
			$new_mail = $cur_user->attribute("email");
			//TODO
			$old_user_node = eZContentObjectTreeNode::fetchByContentObjectID($contentobject_id, true, $user_obj->previousVersion());
			$old_mail = $old_user_node;
			//TODO
			var_dump($old_user_node);
			die("drin");
			if ($new_mail != $old_mail)
			{
				$db = eZDB::instance();
				$old_user = eZUser::fetch();
			}
		}
		/*
			$users = $db->arrayQuery("SELECT user_id, last_visit_timestamp FROM ezuservisit WHERE user_id != $current_userID AND 
     		$db->begin();
		    $db->arrayQuery("INSERT INTO xrowmailchange_notification ( user_id, path_string, timestamp ) VALUES ( $id, '$path_string', $now_time );");
		    $db->commit();
		*/
        return eZWorkflowType::STATUS_ACCEPTED;
    }
}

eZWorkflowEventType::registerEventType( notifyAfterPublishType::WORKFLOW_TYPE_STRING, 'notifyAfterPublishType' );

?>