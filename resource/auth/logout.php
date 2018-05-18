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
include_once '../../object/Auth.php';
include_once '../../object/User.php';
include_once '../../object/Register.php';
include_once '../../service/AuthService.php';
include_once '../../service/UserService.php';
include_once '../../service/RegisterService.php';
include_once '../../util/Constants.php';
include_once '../../util/ValidateHelper.php';

$headers = apache_request_headers();
$token = $headers['Authorization'];

try {
    $db = new Database();
    $conn = $db->getConnection();

    $conn->beginTransaction();

    $authService = new AuthService($conn);
    $authService->end($token);

    $conn->commit();
    echo json_encode(array('title' => 'Logged out', 'message' => 'Session finished', 'success' => true));
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}