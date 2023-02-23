<?php
require_once('../../../config.php');
$objUsuarioRolCon = new UsuarioRolController();
$data = data_submitted();
//lista de roles
$rta = $objUsuarioRolCon->buscarRoles($data);
//rol
$arrayRoles = $objUsuarioRolCon->getRoles();

$rolesSimple = [];
foreach ($arrayRoles as $key => $value) {
    $datas = $value->dameDatos();
    $rolesSimple[$datas['idrol']] = false;
}

//convertir roles del usuario a texto
$rolesTexto = [];
if(count($rta) != 0){
    foreach ($rta as $key => $value) {
        $datas = $value->dameDatos();
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
