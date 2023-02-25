<?php

class UsuarioRolController extends MasterController {
    use Errores;

    public function listarTodo( $arrayBusqueda ){
        $rta = Usuariorol::listar( $arrayBusqueda );
        if( $rta['respuesta'] == true ){
            //conversion
            $data['array'] = $rta['array'];
            $data['arrayHTML'] = [];
            foreach ($data['array'] as $key => $value) {
                $objUsuarioRol = $value;
                $objUsuario = $value->getObjUsuario();
                $objRol = $value->getObjRol();
                $idur = $objUsuarioRol->getIdur();
                $nombre = $objUsuario->getUsnombre();
                $rol = $objRol->getRodescripcion();
                $array['idur'] = $idur;
                $array['nombre'] = $nombre;
                $array['rol'] = $rol;
                array_push($data['arrayHTML'], $array);
            }
        } else {
            $data['error'] = $this->manejarError( $rta );
        }
        return $data;
    }

    //BUSCA ROLES DE LOS USUARIOS
    public function buscarRoles($data){
        $rolusuario = new Usuariorol();
        $rta = $rolusuario::listar($data);
        $listaRoles = [];
        if($rta['respuesta']){
            $listaRoles = $rta['array'];
        }
        return $listaRoles;
    }

    //LISTA LOS ROLES QUE HAY DISPONIBLES
    public function getRoles(){
        $rol = new Rol();
        $arrayBus = [];
        $listaRoles = rol::listar($arrayBus);
        if( isset($listaRoles['array']) ){
            $lista = $listaRoles['array'];
        } else {
            $lista = $listaRoles['respuesta']; 
        }
        return $lista;
    }

    public function getRolesConIdUsuario($idUsuario){
        $arrBUsuario['idusuario'] = $idUsuario;
        $rt = Usuariorol::listar($arrBUsuario);
        if(array_key_exists('array', $rt)){
            //encontro los roles
            $roles = [];
            foreach ($rt['array'] as $key => $value) {
                $objUsuRol = $value->dameDatos();
                array_push($roles, $objUsuRol);
            }
            $response = $roles;
        }else{
            $response = false;
        }
        return $response;
    }

    public function getUsuarios(){
        $arrayBus = [];
        $arrayBus['usdeshabilitado'] = NULL;
        $listaUsuarios = Usuario::listar($arrayBus);
        return $listaUsuarios['array'];
    }

    
    public function baja( $param ){
        $bandera = false;
        if( $param->getIdur !== null ){
            if( $param->eliminar() ){
                $bandera = true;
            }
        }
        return $bandera;
    }

    public function dameDatos(){
        $objUsuarioRol = new Usuariorol();
        $data = [];
        $data['idur'] = $objUsuarioRol->getIdur();
        $objUsuario = $objUsuarioRol->getObjUsuario();
        $data['idusuario'] = $objUsuario->getIdusuario();
        $objUsuario = null;
        $objRol = $objUsuarioRol->getObjRol();
        $data['idrol'] = $objRol->getIdrol();
        $objRol = null;
        return $data;
    }


    //funcion intermediaria entre insertar o eliminar rol
    public function intermediario($datas){
        //PRIMERO SE BUSCA EL ROL DENTRO DEL DATA
        $arrayRolesData = [];
        foreach ($datas as $key => $value) {//key es rol2    y value  on
            if(($key == "rol1")||($key == "rol2")||($key == "rol3")){
                $rol = substr($key,3);//para obtener el rol q le pusieron
                array_push($arrayRolesData,$rol);//los roles que se enviaron en data
            }
        }
        //LISTAR TODOS LOS usuariorol QUE TIENE ESE usuario EN BASE DE DATOS
        $usuarioRol = new Usuariorol();
        $arrayDato['idusuario'] = $datas['idusuario'];
        $encontro = $usuarioRol->listar($arrayDato);
        //si hay array es que ese usuario tiene roles asignados
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
                $resp = $usuarioRol->eliminarUsuarioRol($datas,$arrayIdDiferente);
                if($resp){
                    $salida = true;
                }else{
                    $salida = false;
                }
            }else{
                //insertar
                //para eso necesito el idusuario(q esta en data)}
                $arrayIdDiferente = array_diff($arrayRolesData,$idRolesBd);
                $resp = $usuarioRol->nuevoUsuarioRol($datas,$arrayIdDiferente);
                if($resp){
                    $salida = true;
                }else{
                    $salida = false;
                }
            }       
        }else{
            //es que no hay usuariorol con ese usuario
            $resp = $usuarioRol->nuevoUsuarioRol($datas,$arrayRolesData);//OJO ACA NO ESTA EL IDROL
            if($resp){
                $salida = true;
            }else{
                $salida = false;
            }

        }
        return $salida;
    }
    
    







}