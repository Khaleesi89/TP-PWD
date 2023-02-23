<?php  
require_once('../../config.php');
$objSession = new SessionController();

if( $objSession->getUsnombre() != false ){
    require_once('header2.php');
} else {
    require_once('header.php');
}