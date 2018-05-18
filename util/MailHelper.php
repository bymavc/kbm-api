<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/14/2018
 * Time: 11:50 PM
 * @throws Exception
 */

function sendMail($receiver, $subject, $message){
    if(!mail($receiver, $subject, $message)){
        throw new Exception('Unable to send email');
    }
}