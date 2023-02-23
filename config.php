<?php
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

//define('URL', 'http://localhost/'); HICE CAMBIO ACA..DESCOMENTARLO

////////////////////////
// configuracion app //
////////////////////////

$PROYECTO = 'TP-PWD';

// variable que almacena el directorio del proyecto
$ROOT = $_SERVER['DOCUMENT_ROOT']."/$PROYECTO/";

require_once( $ROOT.'./Util/functions.php');

// variable que define la pagina de autenticacion del proyecto
$INICIO = "Location:http://".$_SERVER['HTTP_HOST']."/$PROYECTO/Views/accion/accionLogin.php";

// variable que define la pagina principal del proyecto (menu principal)
$PRINCIPAL = "Location:http://".$_SERVER['HTTP_HOST']."/$PROYECTO/Views/home/index.php";

//variable para ir a productos
$PRODUCTOS = "Location:http://".$_SERVER['HTTP_HOST']."/$PROYECTO/Views/producto/producto_list.php";
$PROD = "http://".$_SERVER['HTTP_HOST']."/$PROYECTO/Views/producto/producto_list.php";//hice cambios aca

$GLOBALS['ROOT'] = $ROOT;


//autoload de clases
spl_autoload_register(function ($class_name){
    //echo "class ".$class_name ;
    $directorys = array(
        $GLOBALS['ROOT'].'Controllers/',
        $GLOBALS['ROOT'].'Models/',
        $GLOBALS['ROOT'].'Models/conector',
        $GLOBALS['ROOT'].'Public/',
        $GLOBALS['ROOT'].'Util/',
        $GLOBALS['ROOT'].'Vendor/',
        $GLOBALS['ROOT'].'Views/',
        $GLOBALS['ROOT'].'Views/templates',
        $GLOBALS['ROOT'].'Vendor/phpmailer/phpmailer/src',
        

    );
    // print_r($directorys);
    foreach($directorys as $directory){
        if(file_exists($directory.$class_name . '.php')){
            // echo "se incluyo".$directory.$class_name . '.php';
            require_once($directory.$class_name . '.php');
            return;
        }
    }
});


?>