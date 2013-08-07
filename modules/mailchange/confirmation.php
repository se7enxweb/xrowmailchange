<?php

$Module = & $Params['Module'];
$http = eZHTTPTool::instance();
$namedParameters = $Module->NamedParameters;
$tpl = eZTemplate::factory();
$db = eZDB::instance();
$xrowChangeMailINI = eZINI::instance( 'xrowmailchange.ini' );
$hash = $namedParameters['hash'];

$active_request = $db->arrayQuery("SELECT * FROM xrow_mailchange WHERE hash = '$hash';");
if ( count($active_request[0]) >= 1 )
{
    $new_mail = $active_request[0]["new_mail"];
    $user_id = $active_request[0]["user_id"];
    $contentObject = eZContentObject::fetch($user_id, true);
    //add new email address
	//TODO
    $contentObject->setAttribute( 'email', $new_mail );
    $contentObject->store();
    //delete old entry
    $db->begin();
    $db->arrayQuery("DELETE FROM xrow_mailchange WHERE user_id = $user_id;");
    $db->commit();
}
else
{
    $tpl->setVariable( 'error', true );
}

$Result = array();
$Result['content'] = $tpl->fetch( 'design:mailchange/confirmation.tpl' );
$Result['path'] = array(  array( 'url' => false,
                                 'text' =>  ezpI18n::tr( 'extension/xrowmailchange', 'Mail change notification' ) )
);

?>