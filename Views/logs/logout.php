<?php
require_once( '../../config.php' );

$objSession = new SessionController();
$objSession->cerrar();

header('Location: /TP-PWD');

?>