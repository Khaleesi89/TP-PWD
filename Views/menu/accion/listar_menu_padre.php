<?php
require_once('../../../config.php');
$objMenuCon =  new MenuController();
$array = $objMenuCon->listar_menu_padre();
var_dump($array);
echo json_encode($array);