<?php
require_once('../../../config.php');
$objMenuCon = new MenuController();
$retorno['respuesta'] = false;
//borrar roles de menu
$idmenu = $objMenuCon->buscarKey('idmenu');//HACER UN DATA AQUI
if ($idmenu != null) {
    $arraybus['idmenu'] = $idmenu;
    $rolesDeMenu = Menurol::listar($arraybus);
    if($rolesDeMenu['respuesta']){
        if(count($rolesDeMenu['array']) > 0){
            foreach ($rolesDeMenu['array'] as $key => $value) {
                $arrBus['idmr'] = $value->getIdmr();
                $objMenurol = new Menurol();
                $objMenurol->buscar($arrBus);
                $objMenurol->eliminar();
                $objMenurol = null;
               
            }
        }
    }
        //cargar objeto de menu
        $objMenu = new Menu();
        $arrayDeBus['idmenu'] = $idmenu;
        $objMenu->buscar($arrayDeBus);
        //obtener los nuevos roles
        $arrayRoles = $objMenuCon->getRoles();
        $rolesNuevos = [];
        $rolesSimple = [];
        if(count($arrayRoles)>0){
            foreach ($arrayRoles as $key => $value) {
                $data = $value->dameDatos();
                $idrol = $data['idrol'];
                $guardarDato = $objMenuCon->buscarKey(("rol$idrol"));//HACER UN DATA AQUI
                if ($guardarDato != null && $guardarDato == 'on') {
                    $rolesNuevos[$idrol] = $guardarDato;
                }
            }
            //cargar los nuevos roles
            foreach ($rolesNuevos as $key => $value) {
                $aBus['idrol'] = $key;
                if($value == 'on'){
                    $objRol = new Rol();
                    $objRol->buscar($aBus);
                    $objMenurol = new Menurol();
                    $objMenurol->cargar($objMenu, $objRol);
                    $objMenurol->insertar();
                    $objRol = null;
                    $objMenurol = null;
                }
            }
            $retorno['respuesta'] = true;
        }else{
            $mensaje = 'No hay roles cargados';
        }
        
    
    
        
    
    
   
}else{
    $mensaje = 'No se ha podido realizar la operacion';
}
if(isset($mensaje)){
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);
