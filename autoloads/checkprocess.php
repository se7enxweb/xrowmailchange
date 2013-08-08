<?php

class checkMailChangeProcess
{
    function checkMailChangeProcess()
    {
    }

    function operatorList()
    {
        return array('checkmailchangeprocess');
    }
    
    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'checkmailchangeprocess' => array ( 'user_id'  => array( "type" => "integer",
                                                                "required" => true,
                                                                "default" => false  ) ));
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters,  $placement)
    {
        switch ( $operatorName )
        {
           case 'checkmailchangeprocess':
            {
                $db = eZDB::instance();
                $user_id = $namedParameters['user_id'];
                $active_request = $db->arrayQuery("SELECT * FROM xrow_mailchange WHERE user_id = $user_id;");
                if ( count($active_request[0]) >= 1 )
                {
                    $operatorValue =  $active_request[0]["new_mail"];
                }
            } break;
         }
    }
}

?>