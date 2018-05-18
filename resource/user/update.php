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
include_once '../../object/Register.php';
include_once '../../service/AuthService.php';
include_once '../../service/UserService.php';
include_once '../../service/RegisterService.php';
include_once '../../util/Constants.php';
include_once '../../util/FileHelper.php';
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

    if(!password_verify($data->password, $user->getPassword())){
        throw new Exception("Wrong Password");
    }

    if($data->new_password != null){
        $user->setPassword($data->new_password);
    } else {
        $user->setPassword($data->password);
    }

    $user->setEmail($data->email);
    $user->setUsername($data->username);
    $user->setFirstName($data->first_name);
    $user->setLastName($data->last_name);

    if(!validateUser($user)){
        throw new Exception("Invalid format");
    }

    $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));

    if(!is_null($data->profile_picture) && strlen($data->profile_picture) > 140){
        $url = saveImage(
            $data->profile_picture,
            generateName('img', 10),
            PROFILE_PICTURE_DIRECTORY . $user->getId()
        );
        $url = substr($url, strpos($url, 'images/profile/') + strlen('images/profile/'));
        $user->setProfilePicture($url);
    } else {
        $user->setProfilePicture($data->profile_picture);
    }

    $userService->update($user);

    $conn->commit();
    echo json_encode(array('title' => 'User Update', 'message' => 'User information has been updated', 'success' => true));
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}