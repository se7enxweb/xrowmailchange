<?php

$Module = array( 'name' => 'mailchange' );

$ViewList = array();
$ViewList['confirmation'] = array( 'functions' => array( 'confirmation' ),
                                   'script' => 'confirmation.php',
                                   'params' => array( 'hash' ) );

$ViewList['rejection'] = array( 'functions' => array( 'rejection' ),
                                'script' => 'rejection.php',
                                'params' => array( 'hash' ) );

$FunctionList = array();
$FunctionList['confirmation'] = array();
$FunctionList['rejection'] = array();

?>