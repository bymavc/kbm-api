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
include_once '../../util/MailHelper.php';
include_once '../../util/ValidateHelper.php';

$data = json_decode(file_get_contents("php://input"));

try {
    $db = new Database();
    $conn = $db->getConnection();

    $conn->beginTransaction();

    if(!validateEmail($data->email)){
        throw new Exception("Invalid email address");
    }

    $userService = new UserService($conn);
    $user = $userService->getByEmail($data->email);

    $codeService = new CodeService($conn);
    $code = new Code();
    $code->setUser($user->getId());
    $code->setCode($codeService->generateCode(10));
    $code->setType(2);
    $code->setDate(date('Y-m-d h:i:s'));

    $code = $codeService->create($code);

    sendMail(
        $user->getEmail(),
        "Account Recovery",
        "We have received a recovery request from your account! " .
        "Your username is: " . $user->getUsername() .
        ". You can set a new password for your account using this link: " . APPLICATION_URL . "change/password/" . $code->getCode()
    );

    $conn->commit();
    echo json_encode(array('title' => 'Recovery', 'message' => 'We have sent an email to your account', 'success' => true));
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}