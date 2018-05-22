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

    $user = new User();
    $user->setUsername($data->username);
    $user->setEmail($data->email);
    $user->setPassword($data->password);
    $user->setFirstName($data->first_name);
    $user->setLastName($data->last_name);
    $user->setStatus(3);

    if(!validateUser($user)){
        throw new Exception("Invalid format");
    }

    $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));

    $userService = new UserService($conn);
    $user = $userService->create($user);

    $codeService = new CodeService($conn);
    $code = new Code();
    $code->setUser($user->getId());
    $code->setCode($codeService->generateCode(10));
    $code->setType(1);
    $code->setDate(date('Y-m-d h:i:s'));

    $code = $codeService->create($code);

    sendMail(
        $user->getEmail(),
        "Email verification",
        "You have just registered at KBM! " .
        "To verify your account use this code: " . $code->getCode() .
        ", or click this link: " . APPLICATION_URL . "verify/" . $code->getCode()
    );

    $conn->commit();
    echo json_encode(array(
        'title' => 'User Created', 
        'message' => 'A verification email has been sent to your account', 
        'success' => true
    ));
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}