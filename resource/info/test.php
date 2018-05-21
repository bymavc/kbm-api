<?php 
include_once '../../util/ValidateHelper.php';

$string = "This should be a kb name +";

if(validateAlphaNumWithSpaces($string)){
    echo "it works";

} else {
    echo "doesnt work";
}

?>