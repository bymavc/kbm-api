<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/26/2018
 * Time: 10:29 AM
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

    //setting json permissions into php array
    $permissions = (array)$data->permissions;
    $perm_arr = array();
    foreach($permissions as $perm){
        array_push($perm_arr, (array)$perm);
    }

    $kb->setPermissions($perm_arr);

    $register = new Register($user->getId(), date('Y-m-d h:i:s'), 'Updated Permissions');

    $kbService->update($kb, $register);

    $conn->commit();
    echo json_encode(array(
        'title' => 'Updated',
        'message' => 'Collaborators have been updated',
        'success' => true
    ));
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}