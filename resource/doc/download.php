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
include_once '../../object/Document.php';
include_once '../../service/DocumentService.php';
include_once '../../util/Constants.php';
include_once '../../util/ValidateHelper.php';
require_once '../../util/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$id = $_REQUEST['id'];

try {
    $db = new Database();
    $conn = $db->getConnection();

    if(!validateInteger($id)){
        throw new Exception("Not a valid document identifier");
    }

    $docService =  new DocumentService($conn);
    $doc = $docService->getById($id);

    $html = '<h1>' . $doc->getName() . '</h1>';
    $html .= '<p>' . $doc->getDescription() . '</p><hr>';
    $html .= '<p>Tags: ';
    foreach($doc->getTags() as $tag){
        $html .= $tag . '. ';
    }
    $html .= '</p>';
    $html .= $doc->getContent();

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();
    $dompdf->stream($doc->getName());
    
} catch (Exception $e) {
    echo json_encode(array('title' => 'Error', 'message' => $e->getMessage(), 'success' => false));
}