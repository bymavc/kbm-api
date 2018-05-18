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

function validateEmail($string)
{
    return filter_var($string, FILTER_VALIDATE_EMAIL);
}

function validatePassword($string)
{
    $uppercase = preg_match('@[A-Z]@', $string);
    $lowercase = preg_match('@[a-z]@', $string);
    $number = preg_match('@[0-9]@', $string);

    if($uppercase && $lowercase && $number && strlen($string) >= 8)
    {
        return true;
    }
    return false;
}

function validateAlpha($string)
{
    return preg_match("/^[a-z ]+$/i", $string);
}

function validateAlphaNum($string)
{
    return preg_match("/^[a-z0-9]*$/i", $string);
}

function validateUser(User $user)
{
    $v1 = validateEmail($user->getEmail());
    $v2 = validateAlphaNum($user->getUsername());
    $v3 = validatePassword($user->getPassword());
    $v4 = validateAlpha($user->getFirstName());
    $v5 = validateAlpha($user->getLastName());
    $v6 = validateInteger($user->getStatus());

    return ($v1 && $v2 && $v3 && $v4 && $v5 && $v6);
}

function validateCode($code)
{
    return validateAlphaNum($code);
}

function validateKb(KnowledgeBase $kb)
{
    $v1 = validateInteger($kb->getStatus());
    $v2 = validateInteger($kb->getPrivacy());

    return ($v1 && $v2);
}