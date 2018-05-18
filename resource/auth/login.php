<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/16/2018
 * Time: 1:22 PM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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

$data = json_decode(file_get_contents("php://input"));

try {
    $db = new Database();
    $conn = $db->getConnection();

    $conn->beginTransaction();

    $authService = new AuthService($conn);
    $auth = $authService->init($data->username, $data->password);

    $conn->commit();
    echo json_encode(array('title' => 'Logged in', 'message' => 'Session started', 'token' => $auth->getToken(), 'success' => true));
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}