<?php

require_once( '../templates/header.php');
require_once("../../config.php");

$datos = data_submitted();
$respuesta = $objUsuario->insertar($datos);
if( $respuesta['respuesta'] ){
    ?>
    <script>
        location.href = '../home/index.php';
    </script>
    <?php
} else {
    echo('Usuario creado pa la wea');
}