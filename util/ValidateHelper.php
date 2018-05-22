<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/16/2018
 * Time: 1:23 PM
 */

function validateInteger($int)
{
    return preg_match("/^[0-9]*$/i", $int);
}

function validateAlpha($string)
{
    return preg_match("/^[a-z ]+$/i", $string);
}

function validateAlphaNum($string)
{
    return preg_match("/^[a-z0-9]*$/i", $string);
}

function validateAlphaNumWithSpaces($string)
{
    return preg_match("/^[a-z0-9 .\-]+$/i", $string);
}

function validateEmail($string)
{
    if(strlen($string) < 5 || strlen($string) > 60){
        return false;
    }
    return filter_var($string, FILTER_VALIDATE_EMAIL);
}

function validateUsername($string){
    if(strlen($string) < 3 || strlen($string) > 20){
        return false;
    }
    return validateAlphaNum($string);
}

function validatePassword($string)
{
    if(strlen($string) < 8 || strlen($string) > 20){
        return false;
    }

    $uppercase = preg_match('@[A-Z]@', $string);
    $lowercase = preg_match('@[a-z]@', $string);
    $number = preg_match('@[0-9]@', $string);

    if($uppercase && $lowercase && $number && strlen($string) >= 8)
    {
        return true;
    }
    return false;
}

function validateName($string)
{
    if(strlen($string) < 3 || strlen($string) > 20){
        return false;
    }
    return validateAlpha($string);
}

function validateUser(User $user)
{
    $v1 = validateEmail($user->getEmail());
    $v2 = validateUsername($user->getUsername());
    $v3 = validatePassword($user->getPassword());
    $v4 = validateName($user->getFirstName());
    $v5 = validateName($user->getLastName());
    $v6 = validateInteger($user->getStatus());

    return ($v1 && $v2 && $v3 && $v4 && $v5 && $v6);
}

function validateCode($string)
{
    if(strlen($string) < 10 || strlen($string) > 10){
        return false;
    }
    return validateAlphaNum($string);
}

function validateKb(KnowledgeBase $kb)
{
    $v1 = validateInteger($kb->getStatus());
    $v2 = validateInteger($kb->getPrivacy());
    $v3 = validateAlphaNumWithSpaces($kb->getName());

    
    return ($v1 && $v2);
}