<?php
require_once('../../../config.php');
$objMenuRol = new MenuRolController();
$rta = $objMenuRol->buscarRoles();
$objUsuarioRolCon = new UsuarioRolController();
$arrayRoles = $objUsuarioRolCon->getRoles();
$rolesSimple = [];
foreach ($arrayRoles as $key => $value) {
    $data = $value->dameDatos();
    $rolesSimple[$data['idrol']] = false;
}
//convertir roles del usuario a texto
$rolesTexto = [];
if(count($rta) != 0){
    foreach ($rta as $key => $value) {
        $data = $value->dameDatos();
        $rolesTexto[$data['idrol']] = true;
    }
}
$string = "";
$arrayOtro = [];
if(count($rolesTexto) != 0){
    foreach ($rolesSimple as $id => $idrolArray) {
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
