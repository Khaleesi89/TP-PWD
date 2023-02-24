<?php
class MenuController extends MasterController {
    use Errores;

    public function listarTodo($arralgo = NULL){
        if($arralgo == NULL){
            $arrBu = [];
        }else{
            $arrBu = $arralgo;
        }
        $arrayTotal = Menu::listar($arrBu);
        if(array_key_exists('array', $arrayTotal)){
            $array = $arrayTotal['array'];
        }else{
            $array = [];
        }
        return $array;
    }


    //buscar menu por id

    public function buscar($data){
        $menu = new Menu();
        $rta = $menu->buscar($data);
        if($rta['respuesta']){
            $salida = $menu;
        }else{
            $salida = false;
        }
       
        return $salida;
    }



    public function insertar($data){
        $objMenu = new Menu();
        $objMenu->setMenombre($data['menombre']);
        $objMenu->setMedescripcion($data['medescripcion']);
        $objPadre = new Menu();
        $arrayBus['idmenu'] = $data['idpadre'];
        $objPadre->buscar($arrayBus);
        $objMenu->setObjPadre($objPadre);
        $objMenu->setMedeshabilitado(NULL);
        $rta = $objMenu->insertar();
        return $rta;
    }

    public function modificar($datosEnviados){
        $response = false;
        $objMenu = new Menu();
        $arrayBusqueda = ['idmenu' => $datosEnviados['idmenu']];
        $encontro = $objMenu->buscar($arrayBusqueda);
        if($encontro['respuesta']){
            $objMenu->cargar($datosEnviados['menombre'], $datosEnviados['medescripcion'], $datosEnviados['idpadre']);
            $rsta = $objMenu->modificar();
            if($rsta['respuesta']){
                $response = true;
            }
            
        }       
        
        return $response;
    }

 
    public function getRoles(){
        $arrayBus = [];
        $listaRoles = Rol::listar($arrayBus);
        return $listaRoles['array'];
    }

    public function obtenerMenuesPorRol($idrol){
        $arrayBu['idrol'] = $idrol;
        
        $arrayMenues = Menurol::listar($arrayBu);
        
        $arrayRepasado = [];
        foreach ($arrayMenues['array'] as $key => $value) {
            $objMenurol = $value;
            
            $datos = $objMenurol->dameDatosMenues();
            array_push($arrayRepasado, $datos);
        }
        $arrayHijos = [];
        foreach ($arrayRepasado as $key => $value) {
            $datosMenu = $value['idmenu'];
            $nombreMenu = $datosMenu['menombre'];
            array_push($arrayHijos, $nombreMenu);
        }
        return $arrayHijos;
    }    


}