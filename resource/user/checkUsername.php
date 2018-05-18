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


$db = new Database();
$conn = $db->getConnection();

$userService = new UserService($conn);

if($userService->checkUsername($_REQUEST['username'])){
    echo json_encode(array('title' => 'Username verification', 'message' => 'Username is already taken', 'success' => false));
} else {
    echo json_encode(array('title' => 'Username verification', 'message' => 'Username is available', 'success' => true));
}