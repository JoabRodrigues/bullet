<?php

include_once '../objects/user.php';

class Validation{


    function validaToken($token,$organization,$db){
        
        if(is_null($token) || is_null($organization)){
            $arrValidaToken = array(
                "message" => "No token found.",
                "userid" => null 
            );
        }else{
            // valida o token do usuário
            $user = new User($db);
            $userid = $user->validaToken($token,$organization);

            
            if(is_null($userid)){
                $arrValidaToken = array(
                    "message" => "token is not valid",
                    "userid" => null 
                );
            }else{
                $arrValidaToken = array(
                    "message" => "token is valid",
                    "userid" => $userid
                );
            }
        }
        return $arrValidaToken;
    }

}
?>