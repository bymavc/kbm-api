<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 5/6/2018
 * Time: 4:00 PM
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/Database.php';
include_once '../../service/TagService.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    $tagService =  new TagService($conn);
    $tags = $tagService->getAll();

    echo json_encode(array(
        'tags' => $tags,
        'success' => true
    ));
} catch (Exception $e) {
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}