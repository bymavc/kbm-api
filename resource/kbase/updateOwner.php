<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/26/2018
 * Time: 10:44 AM
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

    if(!validateInteger($data->id) || !validateInteger($data->new_owner)){
        throw new Exception("Not a valid identifier");
    }

    $kbService = new KnowledgeBaseService($conn);
    $kb = $kbService->getById($data->id);

    if(!$kbService->checkPermission($user->getId(), $kb->getId(), 'own')){
        throw new Exception("User has no permission for this operation");
    }

    if(!strcmp($data->name, $kb->getName()) == 0){
        throw new Exception("Wrong Name");
    }

    $permission = $kb->getPermissions();

    $has_permission = false;

    foreach ($permission as &$perm){
        if($perm['role'] == 1){
            $perm['role'] = 2;
        }
        if($perm['user'] == $data->new_owner){

            $perm['role'] = 1;
            $has_permission = true;
        }
    }

    if(!$has_permission){
        $new_perm = array('user' => $data->new_owner, 'role' => 1);
        array_push($permission, $new_perm);
    }

    $kb->setPermissions($permission);

    $register = new Register($user->getId(), date('Y-m-d h:i:s'), 'Updated Owner');

    $kbService->update($kb, $register);

    $conn->commit();
    echo json_encode(array(
        'title' => 'Updated',
        'message' => 'Owner has changed',
        'success' => true
    ));
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}