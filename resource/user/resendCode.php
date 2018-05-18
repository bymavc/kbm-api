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
include_once '../../service/UserService.php';
include_once '../../service/CodeService.php';
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

    try{
        $code = $codeService->getByUserAndType($user->getId(), 1);
        $code->setCode($codeService->generateCode(10));
        $code->setDate(date('Y-m-d h:i:s'));

        $code = $codeService->update($code);
    } catch (Exception $e) {
        $code = new Code();
        $code->setUser($user->getId());
        $code->setType(1);
        $code->setCode($codeService->generateCode(10));
        $code->setDate(date('Y-m-d h:i:s'));

        $code = $codeService->create($code);
    }

    sendMail(
        $user->getEmail(),
        "Email verification",
        "You have requested a new verification code. " .
        "To verify your account use this code: " . $code->getCode() .
        ", or click this link: " . APPLICATION_URL . "verify/" . $code->getCode()
    );

    $conn->commit();
    echo json_encode(array('title' => 'Resend', 'message' => 'A verification email has been sent to your account', 'success' => true));
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}