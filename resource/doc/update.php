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
include_once '../../object/Document.php';
include_once '../../object/User.php';
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

    $docService = new DocumentService($conn);
    $doc = $docService->getById($data->id);

    $kbService = new KnowledgeBaseService($conn);
    $kb = $kbService->getById($doc->getKnowledgeBase());

    if(!$kbService->checkPermission($user->getId(), $kb->getId(), "work")){
        throw new Exception("User has no permission for this operation");
    }

    $doc->setName($data->name);
    $doc->setDescription($data->description);
    $doc->setContent($data->content);

    $register = new Register($user->getId(), date('Y-m-d h:i:s'), 'Update Document');

    $docService->update($doc, $register);

    $tagService = new TagService($conn);
    $tagService->tagManager($doc, $data->tags);

    $conn->commit();
    echo json_encode(array(
        'title' => 'Document Updated',
        'message' => 'All changes saved',
        'success' => true
    ));
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}