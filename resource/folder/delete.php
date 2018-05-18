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
include_once '../../object/Document.php';
include_once '../../object/KnowledgeBase.php';
include_once '../../object/Folder.php';
include_once '../../object/Register.php';
include_once '../../object/Tag.php';
include_once '../../service/AuthService.php';
include_once '../../service/UserService.php';
include_once '../../service/DocumentService.php';
include_once '../../service/KnowledgeBaseService.php';
include_once '../../service/FolderService.php';
include_once '../../service/RegisterService.php';
include_once '../../service/TagService.php';
include_once '../../util/Constants.php';

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

    $folderService =  new FolderService($conn);
    $folder = $folderService->getById($data);

    $kbService = new KnowledgeBaseService($conn);
    $kb = $kbService->getById($folder->getKnowledgeBase());

    if(!$kbService->checkPermission($user->getId(), $kb->getId(), "work")){
        throw new Exception("User has no permission for this operation");
    }

    $folderService->delete($folder);

    $conn->commit();
    echo json_encode(array(
        'title' => 'Folder Deleted',
        'message' => 'You have just deleted a folder',
        'success' => true
    ));
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}