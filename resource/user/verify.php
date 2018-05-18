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
include_once '../../object/User.php';
include_once '../../object/Code.php';
include_once '../../object/Register.php';
include_once '../../service/UserService.php';
include_once '../../service/CodeService.php';
include_once '../../service/RegisterService.php';
include_once '../../util/Constants.php';
include_once '../../util/ValidateHelper.php';

$data = json_decode(file_get_contents("php://input"));

try {
    $db = new Database();
    $conn = $db->getConnection();

    $conn->beginTransaction();

    if(!validateCode($data->code)){
        throw new Exception("Invalid format");
    }

    $codeService = new CodeService($conn);
    $code = $codeService->getByCode($data->code);

    $userService = new UserService($conn);
    $user = $userService->getById($code->getUser());

    $user->setStatus(1);

    $userService->update($user);

    $codeService->releaseCode($code->getId());

    $conn->commit();
    echo json_encode(array('title' => 'Email Verified', 'message' => 'Feel free to login whenever you wish', 'success' => true));
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}