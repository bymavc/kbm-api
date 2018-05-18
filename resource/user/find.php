<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/24/2018
 * Time: 6:54 PM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';
include_once '../../object/User.php';
include_once '../../object/Auth.php';
include_once '../../service/UserService.php';
include_once '../../service/AuthService.php';

$headers = apache_request_headers();
$token = $headers['Authorization'];
$pattern = $_REQUEST['pattern'];

try {
    $db = new Database();
    $conn = $db->getConnection();

    $authService = new AuthService($conn);
    $auth = $authService->getAuth($token);

    $userService = new UserService($conn);
    $result = $userService->find($pattern);

    echo json_encode(array(
        'users' => $result,
        'success' => true
    ));
} catch (Exception $e) {
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}