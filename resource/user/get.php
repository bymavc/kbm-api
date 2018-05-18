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
include_once '../../object/Auth.php';
include_once '../../service/UserService.php';
include_once '../../service/AuthService.php';

$headers = apache_request_headers();
$token = $headers['Authorization'];
$user = $_REQUEST['user'];
$owner = false;

try {
    $db = new Database();
    $conn = $db->getConnection();

    $authService = new AuthService($conn);
    $auth = $authService->getAuth($token);

    $userService = new UserService($conn);
    $user0 = $userService->getById($auth->getUser());
    $user = $userService->getByUsername($user);

    if($user0->getId() === $user->getId()){
        $owner = true;
    }

    echo json_encode(array(
        'success' => true,
        'owner' => $owner,
        'username' => $user->getUsername(),
        'email' => $user->getEmail(),
        'first_name' => $user->getFirstName(),
        'last_name' => $user->getLastName(),
        'profile_picture' => $user->getProfilePicture(),
        'register' => $user->getRegister()
    ));
} catch (Exception $e) {
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}