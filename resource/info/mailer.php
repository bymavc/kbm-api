<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../util/ValidateHelper.php';
include_once '../../util/MailHelper.php';

$data = json_decode(file_get_contents("php://input"));

try {

    sendMail(
        'miicasel@gmail.com',
        $data->subject,
        $data->name . " has use KBM Mailer. 
        Email: " . $data->email . "
        Message:
        " . $data->message 
    );

    echo json_encode(array(
        'title' => 'Mail Sent', 
        'message' => 'You have sent an email', 
        'success' => true
    ));
} catch (Exception $e) {
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}


?>