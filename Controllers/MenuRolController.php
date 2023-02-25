<?php

class MenuRolController extends MasterController {
    use Errores;


    public function listarTodo() {
        $arrayBusqueda = [];
        $arrayTotal = Menurol::listar( $arrayBusqueda );
        $array = $arrayTotal['array'];
        return $array;
    }

    public function insertar( $data ){
        $newMenuRol = new Menurol();
        $newMenuRol->setIdmr( $data['idmr'] );
        $newMenuRol->setObjMenu( $data['objMenu'] );
        $newMenuRol->setObjRol( $data['objRol'] );

        $operacion = $newMenuRol->insertar();
        if( $operacion['respuesta'] == false ){
            $rta = $operacion['errorInfo'];
        } else {
            $rta = $operacion['respuesta'];
        }
        return $rta;
    }



    public function baja( $param ){
        $bandera = false;
        if( $param->getIdmr() !== null ){
            if( $param->eliminar() ){
                $bandera = true;
            }
        }
        return $bandera;
    }

    public function buscarRoles($idmenu){
        $menurol = new Menurol;
        $rta = $menurol::listar($idmenu);
        if($rta['respuesta']){
            $arr = $rta['array'];
        }else{
            $arr = [];
        }
        return $arr;    
    }
    

    //para ver si exisste ese menu o no cargado
    //se hace porq en el data manda lo q esta marcado entonces
    //hay que filtrar

    public function existeOno($datas){
        $arrayB['idrol'] = $datas['idrol'];
        $arrayB['idmenu'] = $datas['idmenu'];
        $menuRol = new Menurol();
        $esta = $menuRol->buscar($arrayB);
        if($esta['respuesta']){
            $salida = true;
        }else{
            $salida = false;
        }
        return $salida;
    }


     /* public function eliminarMenuRol($data){
        $menuRol = new Menurol();
        $rol = $data['idrol'];
        $menu = $data['idmenu'];
        $arrayBus['idrol'] = $rol;
        $arrayBus['idmenu'] = $menu;
        $encontro = $menuRol->buscar($arrayBus);
        if($encontro['respuesta']){
            $rta = $menuRol->eliminar();
            if($rta['respuesta']){
                $salida = true;
            }else{
                $salida = false;
            }
        }
        return $salida;
    
    } */

    //FUNCION QUE PRIMERO LISTA TODOS LOS ROLES QUE TIENE ESE MENU
    //SI ESE ROL Q VIENE POR DATA YA NO ESTA, CREA EL MENUROL
    //
    public function intermediario($datas){
        //PRIMERO SE BUSCA EL ROL DENTRO DEL DATA
        $arrayRolesData = [];
        foreach ($datas as $key => $value) {//key es rol2    y value  on
            if(($key == "rol1")||($key == "rol2")||($key == "rol3")){
                $rol = substr($key,3);//para obtener el rol q le pusieron
                array_push($arrayRolesData,$rol);//los roles que se enviaron en data
            }
        }
        //LISTAR TODOS LOS MENUROL QUE TIENE ESE MENU EN BASE DE DATOS
        $menuRol = new Menurol();
        $arrayDato['idmenu'] = $datas['idmenu'];
        $encontro = $menuRol->listar($arrayDato);
        //SI HAY ARRAY ES QUE HAY IDMENU CON ROLES YA DECLARADOS
        if(array_key_exists('array',$encontro)){
            $lista = $encontro['array'];
            $idRolesBd = [];
            //SACAMOS LOS IDROL DE LA BASE DE DATOS DE ESOS MENU ENCONTRADOS
            foreach ($lista as $key => $value) {
                $idRolBd = $value->getObjRol()->getIdrol();
                array_push($idRolesBd,$idRolBd);
            }
            if(count($idRolesBd) > count($arrayRolesData)){
                //eliminar
                $arrayIdDiferente = array_diff($idRolesBd,$arrayRolesData);
                $resp = $menuRol->eliminarMenuRol($datas,$arrayIdDiferente);
                if($resp){
                    $salida = true;
                }else{
                    $salida = false;
                }
            }else{
                //insertar
                //para eso necesito el idmenu(q esta en data)}
                $arrayIdDiferente = array_diff($arrayRolesData,$idRolesBd);
                $resp = $menuRol->nuevoMenuRol($datas,$arrayIdDiferente);
                if($resp){
                    $salida = true;
                }else{
                    $salida = false;
                }
            }       
        }else{
            //EN ESTE CASO NO HAY IDMENU CON ESE ROL ASIQ HAY Q CREARLO
            $resp = $menuRol->nuevoMenuRol($datas,$arrayRolesData);//OJO ACA NO ESTA EL IDROL
            if($resp){
                $salida = true;
            }else{
                $salida = false;
            }
            
        }
        return $salida;
    }


    
}
