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
include_once '../../object/Document.php';
include_once '../../object/KnowledgeBase.php';
include_once '../../object/Folder.php';
include_once '../../object/Register.php';
include_once '../../service/AuthService.php';
include_once '../../service/UserService.php';
include_once '../../service/DocumentService.php';
include_once '../../service/KnowledgeBaseService.php';
include_once '../../service/FolderService.php';
include_once '../../service/RegisterService.php';
include_once '../../util/Constants.php';

$id = $_REQUEST['id'];
$headers = apache_request_headers();
$permission = null;

try {
    $db = new Database();
    $conn = $db->getConnection();

    $folderService =  new FolderService($conn);
    $folder = $folderService->getById($id);

    $kbService = new KnowledgeBaseService($conn);
    $kb = $kbService->getById($folder->getKnowledgeBase());

    if($kb->getPrivacy() != 1)
    {
        $token = $headers['Authorization'];
        if(is_null($token)){
            throw new Exception("Users need to be logged in");
        }
        $authService = new AuthService($conn);
        $auth = $authService->getAuth($token);

        $userService = new UserService($conn);
        $user = $userService->getById($auth->getUser());

        if(!$kbService->checkPermission($user->getId(), $kb->getId(), "read")){
            throw new Exception("User has no permission for this operation");
        }
        $permission = $kbService->getUserPermission($user->getId(), $kb->getId());
    }

    echo json_encode(array(
        'knowledge_base' => array(
            'id' => $kb->getId(),
            'name' => $kb->getName()
        ),
        'folder' => array(
            'id' => $folder->getId(),
            'name' => $folder->getName(),
            'parent_folder' => $folder->getParentFolder(),
            'contents' => $folder->getContents()
        ),
        'role' => $permission,
        'success' => true
    ));
} catch (Exception $e) {
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}