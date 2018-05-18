<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/26/2018
 * Time: 10:24 AM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';
include_once '../../object/Auth.php';
include_once '../../object/User.php';
include_once '../../object/KnowledgeBase.php';
include_once '../../object/Folder.php';
include_once '../../object/Register.php';
include_once '../../service/AuthService.php';
include_once '../../service/UserService.php';
include_once '../../service/KnowledgeBaseService.php';
include_once '../../service/FolderService.php';
include_once '../../service/RegisterService.php';
include_once '../../util/Constants.php';
include_once '../../util/ValidateHelper.php';

$headers = apache_request_headers();

$token = $headers['Authorization'];

$data = json_decode(file_get_contents("php://input"));

try {
    $db = new Database();
    $conn = $db->getConnection();

    $conn->beginTransaction();

    $authService = new AuthService($conn);
    $auth = $authService->getAuth($token);

    $userService = new UserService($conn);
    $user = $userService->getById($auth->getUser());

    $kbService = new KnowledgeBaseService($conn);
    $kb = $kbService->getById($data->id);

    if(!$kbService->checkPermission($user->getId(), $kb->getId(), 'own')){
        throw new Exception("User has no permission for this operation");
    }

    if(!strcmp($data->name, $kb->getName()) == 0){
        throw new Exception("Wrong Name");
    }

    $kb->setPrivacy($data->privacy);

    $privacy = ($kb->getPrivacy() == 1) ? 'Public':'Private';

    $register = new Register($user->getId(), date('Y-m-d h:i:s'), 'Change Privacy, Set ' . $privacy);

    $kbService->update($kb, $register);

    $conn->commit();
    echo json_encode(array(
        'title' => 'Updated',
        'message' => 'Knowledge Base now is ' . $privacy,
        'success' => true
    ));
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}