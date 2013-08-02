<?php

$Module = & $Params['Module'];
$http = eZHTTPTool::instance();
$namedParameters = $Module->NamedParameters;
$tpl = eZTemplate::instance();
$db = eZDB::instance();
$mailchangeINI = eZINI::instance( 'xrowmailchange.ini' );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:mailchange/confirmation.tpl' );
$Result['path'] = array(  array( 'url' => false,
                                 'text' =>  ezpI18n::tr( 'extension/xrowmailchange', 'Mail change notification' ) )
);

?>