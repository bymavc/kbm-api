<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/20/2018
 * Time: 10:00 PM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';
include_once '../../service/UserService.php';
include_once '../../util/ValidateHelper.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    $userService = new UserService($conn);

    if(!validateUsername($_REQUEST['username'])){
        throw new Exception("Invalid username address");
    }

    if($userService->checkUsername($_REQUEST['username'])){
        echo json_encode(array('title' => 'Username verification', 'message' => 'Username is already taken', 'success' => false));
    } else {
        echo json_encode(array('title' => 'Username verification', 'message' => 'Username is available', 'success' => true));
    }
} catch (Exception $e){
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}