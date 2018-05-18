<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/16/2018
 * Time: 12:05 PM
 * @throws Exception
 */

function saveImage($data, $name, $location)
{
    if(preg_match('/^data:image\/(\w+);base64,/', $data, $type)){
        $data = substr($data, strpos($data, ',') + 1);
        $type = strtolower($type[1]);

        if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])){
            throw new Exception('Invalid image type');
        }

        $data = base64_decode($data);

        if($data === false){
            throw new Exception('base64_decode failed');
        }
    }else {
        throw new Exception('Did not match data URI with image data');
    }
    if(!file_exists($location)){
        mkdir($location, 0777, true);
    }
    $location = $location . '/' . $name . '.' . $type;
    file_put_contents($location, $data);

    return $location;
}

function generateName($type, $length)
{
    $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $string_length = strlen($string);
    $name = $type;
    while(strlen($name) < $length){
        $name .= $string[rand(0, $string_length - 1)];
    }
    return $name;
}