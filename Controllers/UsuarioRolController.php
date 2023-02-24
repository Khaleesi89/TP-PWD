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

    public function buscarRoles($data){
        $rta = Usuariorol::listar($data);
        $listaRoles = [];
        if($rta['respuesta']){
            $listaRoles = $rta['array'];
        }
        return $listaRoles;
    }


    public function getRoles(){
        $arrayBus = [];
        $listaRoles = Rol::listar($arrayBus);
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
}