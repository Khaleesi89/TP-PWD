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

    

    public function listar_menu_padre(){
        $idmenu = Data::buscarKey('idmenu');
        $array = Menu::darMenuesSinMenu($idmenu);
        return $array;
    }

    public function busqueda(){
        $arrayBusqueda = [];
        $idmenu = Data::buscarKey('idmenu');
        $menombre = Data::buscarKey('menombre');
        $medescripcion = Data::buscarKey('medescripcion');
        $idpadre = Data::buscarKey('idpadre');
        $medeshabilitado = Data::buscarKey('medeshabilitado');
        $arrayBusqueda = ['idmenu' => $idmenu,
                          'menombre' => $menombre,
                          'medescripcion' => $medescripcion,
                          'idpadre' => $idpadre,
                          'medeshabilitado' => $medeshabilitado];
        return $arrayBusqueda;
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

    public function buscarId(){          
        $respuesta['respuesta'] = false;
        $respuesta['obj'] = null;
        $respuesta['error'] = '';
        $arrayBusqueda = [];
        $arrayBusqueda['idmenu'] = Data::buscarKey('idmenu');
        $objMenu = new Menu();
        $rta = $objMenu->buscar($arrayBusqueda);
        if($rta['respuesta']){
            $respuesta['respuesta'] = true;
            $respuesta['obj'] = $objMenu;
        }else{
            $respuesta['error'] = $rta;
        }
        return $respuesta;        
    }

    public function eliminar(){
        $rta = $this->buscarId();
        $response = false;
        if($rta['respuesta']){
            $objMenu = $rta['obj'];
            $respEliminar = $objMenu->eliminar();
            if($respEliminar['respuesta']){
                $response = true;
            }
        }else{
            //no encontro el obj
            $response = false;
        }
        return $response;
    }

    public function Noeliminar(){
        $rta = $this->buscarId();
        $response = false;
        if($rta['respuesta']){
            $objMenu = $rta['obj'];
            $respEliminar = $objMenu->Noeliminar();
            if($respEliminar['respuesta']){
                $response = true;
            }
        }else{
            //no encontro el obj
            $response = false;
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