<?php
require_once('../../../config.php');
$objUsuarioRolCon = new UsuarioRolController();
$data = data_submitted();//['idusuario]
//lista de roles DEL USUARIO
$rta = $objUsuarioRolCon->buscarRoles($data);
//rolES totales
$arrayRoles = $objUsuarioRolCon->getRoles();//trae todos los roles en array

$rolesSimple = [];
foreach ($arrayRoles as $key => $value) {
    $datas = $value->dameDatos();//[idrol] => [rodescripcion] =>  
    $rolesSimple[$datas['idrol']] = false;//me mostrara lositems [1] =>[2] => [3] => VACIOS
    
}
//convertir roles del usuario a texto
$rolesTexto = [];
if(count($rta) != 0){
    foreach ($rta as $key => $value) {
        $datas = $value->dameDatos();//datas tiene [idur] => [idusuario] => [idrol] => 
        $rolesTexto[$datas['idrol']] = true;
    }
}
$string = "";
$arrayOtro = [];
if(count($rolesTexto) != 0){
    foreach ($rolesSimple as $id => $idrolArray) {//idrolarray esta vacio
        $valor = 'false';
        if(array_key_exists($id, $rolesTexto)){
            $rolesSimple[$id] = true;
            $valor = 'true';
        }
        $arrayOtro["rol$id"] = $valor;
        if($string == ''){
            $string.="[$id => $valor,";
        }else{
            $string.= " $id => $valor,";
        }
    }
}
$string = substr($string, 0, -1);
$string .= "] ";
$objNuevo = (object)array('data' => $arrayOtro);
echo json_encode($objNuevo);
