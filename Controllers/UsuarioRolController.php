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

    public function busqueda(){
        $arrayBusqueda = [];
        $idusuario = Data::buscarKey( 'idur' );
        $usnombre =Data::buscarKey( 'usnombre' );
        $rol = Data::buscarKey( 'rol' );
        $arrayBusqueda = [
            'idusuario' => $idusuario,
            'usnombre' => $usnombre,
            'uspass' => $rol
        ];
        return $arrayBusqueda;
    }

    public function buscarRoles($data){
        $rta = Usuariorol::listar($data);
        $listaRoles = [];
        if($rta['respuesta']){
            $listaRoles = $rta['array'];
        }
        return $listaRoles;
    }

    public function buscarId() {
        $idBusqueda = Data::buscarKey( 'idur' );
        if( $idBusqueda == false ){
            // Error
            $data['error'] = $this->warning( 'No se ha encontrado dicho registro' );
        } else {
            // Se encontró
            $array['idur'] = $idBusqueda;
            $usuarioRol = new Usuariorol();
            $rta = $usuarioRol->buscar( $array );
            if( $rta['respuesta'] == false ){
                $data['error'] = $this->manejarError( $rta );
            } else {
                $data['array'] = $usuarioRol;
            }
        }
        return $data;
    }

    public function buscarNombreUsuario() {
        $idBusqueda = $this->buscarKey( 'usnombre' );
        if( $idBusqueda == false ){
            // Error
            $data['error'] = $this->warning( 'No se ha encontrado dicho registro' );
        } else {
            // Se encontró
            $data = $idBusqueda;
        }
        return $data;
    }

    public function insertar() {
        $newUsuarioRol = new Usuariorol();
        $data = $this->busqueda();

        $newUsuarioRol->setIdur( $data['idur'] );
        $newUsuarioRol->setObjUsuario( $data['objUsuario'] );
        $newUsuarioRol->setObjRol( $data['objRol'] );

        $operacion = $newUsuarioRol->insertar();
        if( $operacion['respuesta'] == false ){
            $rta = $operacion['errorInfo'];
        } else {
            $rta = $operacion['respuesta'];
        }
        return $rta;
    }

    public function modificacionChetita() {
        $rta = $this->buscarId();
        $usuarioRol = $rta['array'];

        $idur = $this->buscarKey( 'idur' );
        $objUsuario = $this->buscarKey( 'objUsuario' );
        $objRol = $this->buscarKey( 'objRol' );

        $usuarioRol->setIdur( $idur );
        $usuarioRol->setObjUsuario( $objUsuario );
        $usuarioRol->setObjRol( $objRol );

        $respuesta = $usuarioRol->modificar();
        return $respuesta;
    }

    public function eliminar(){
        $response = false;
        $arrayBus['idur'] = Data::buscarKey('idur');
        $objUsuarioRol = new UsuarioRol();
        $rta = $objUsuarioRol->buscar($arrayBus);
        if($rta['respuesta']){
            $rtaa = $objUsuarioRol->eliminar();
            if($rtaa['respuesta']){
                //se elimino
                $response = true;
            }
        }
        return $response;
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

    public function obtenerNuevosRoles(){
        $roles = $this->getRoles();
        $arrayRoles = [];
        foreach ($roles['array'] as $key => $value) {
            $data = $value->dameDatos();
            $array['idrol'] = $data['idrol'];
            $array['rodescripcion'] = $data['rodescripcion'];
            array_push($arrayRoles, $array);
        }
        $arrayRta = [];
        foreach ($arrayRoles as $key => $value) {
            $rodescripcion = $value['rodescripcion'];
            $input = Data::buscarKey("$rodescripcion");
            if($input != null){
                $campo = true;
            }else{
                $campo = false;
            }
            $arr[$rodescripcion] = $campo;
            array_push($arrayRta, $arr);
        }
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