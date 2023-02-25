<?php
require_once('../../../config.php');
$objMenuRol = new MenuRolController();
$dato = data_submitted();//[idmenu] =>
$rta = $objMenuRol->buscarRoles($dato);
$objUsuarioRolCon = new UsuarioRolController();
$arrayRoles = $objUsuarioRolCon->getRoles();//muestra todos los roles que tienen ese menu disponible
$rolesSimple = [];
foreach ($arrayRoles as $key => $value) {
    $data = $value->dameDatos();
    $rolesSimple[$data['idrol']] = false;// [1] => [2] => [3] => 
}
//convertir roles del usuario a texto
$rolesTexto = [];
if(count($rta) != 0){
    foreach ($rta as $key => $value) {
        $data = $value->dameDatos();
        $rolesTexto[$data['idrol']] = true;
    }
}
//verEstructura($rolesTexto);//[1] => 1[2] => 1[3] => 1 esto esta bien
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
echo json_encode($objNuevo);//este se usa para tener tildados los tildes de los usuarios que tienen
//disponible esos menu
