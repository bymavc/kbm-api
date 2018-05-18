<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/16/2018
 * Time: 1:22 PM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';
include_once '../../service/UserService.php';


$db = new Database();
$conn = $db->getConnection();

$userService = new UserService($conn);

if($userService->checkEmail($_REQUEST['email'])){
    echo json_encode(array('title' => 'Email verification', 'message' => 'Email is already in use', 'success' => false));
} else {
    echo json_encode(array('title' => 'Email verification', 'message' => 'Email is available', 'success' => true));
}