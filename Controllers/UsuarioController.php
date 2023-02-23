<?php

class UsuarioController extends MasterController {
    use Errores;

    public function listarTodo($arrAlgo = NULL){
        if($arrAlgo == NULL){
            $arr = [];
        }else{
            $arr = $arrAlgo;
        }
        $arrayTotal = Usuario::listar($arr);
        if(array_key_exists('array', $arrayTotal)){
            $array = $arrayTotal['array'];
        }else{
            $array = [];
        }
        return $array;        
    }

   //BUSCAR USUARIO, como parametro viene el idusuario

   public function buscarUsuario($array){
        $persona = new Usuario();
        $respuesta = "";
        $usuario = $persona->buscar($array);
        if($usuario['respuesta']){
            $respuesta =$persona;
        }else{
            $respuesta = false;
        }
    
        return $respuesta;
    
   
   }

    public function busqueda(){
        $arrayBusqueda = [];
        $idusuario = Data::buscarKey('idusuario');
        $usnombre = Data::buscarKey('usnombre');
        $uspass = Data::buscarKey('uspass');
        $usmail = Data::buscarKey('usmail');
        $usdeshabilitado = Data::buscarKey('usdeshabilitado');
        $arrayBusqueda = ['idusuario' => $idusuario,
                          'usnombre' => $usnombre,
                          'uspass' => $uspass,
                          'usmail' => $usmail,
                          'usdeshabilitado' => $usdeshabilitado];
        return $arrayBusqueda;
    }

    public function buscarId(){
        $respuesta['respuesta'] = false;
        $respuesta['obj'] = null;
        $respuesta['error'] = '';
        $arrayBusqueda = [];
        $arrayBusqueda['idusuario'] = Data::buscarKey('idusuario');
        $objUsuario = new Usuario();
        $rta = $objUsuario->buscar($arrayBusqueda);
        if($rta['respuesta']){
            $respuesta['respuesta'] = true;
            $respuesta['obj'] = $objUsuario;
        }else{
            $respuesta['error'] = $rta;
        }
        return $respuesta;        
    }


    public function insertar($datos){
        $persona = new Usuario();
        $persona->setUsnombre($datos['usnombre']);
        $persona->setUspass($datos['uspass']);
        $persona->setUsmail($datos['usmail']);
        $rta = $persona->insertar();
        if($rta){
            $salida = true;
        }else{
            $salida = false;
        }
        return $salida;
    }


    public function modificar($data){
        $persona = new Usuario();
        $id = $data['idusuario'];
        $name = $data['usnombre'];
        $pass = $data['uspass'];
        $mail = $data['usmail'];
        $array['idusuario'] = $id;
        $mm = $persona->buscar($array);
        if($mm){
            $persona->setUsnombre($name);
            $persona->setUspass($pass);
            $persona->setUsmail($mail);
            $rta = $persona->modificar();
            if($rta){
                $response = true;
            }else{
                $response = false;
            }
        }else{
            $response = false;

        }
        return $response;
    }

    public function modificacionChetita() {
        $rta = $this->buscarId();
        $usuario = $rta['array'];

        $usNombre = $this->buscarKey( 'usnombre' );
        $usPass = $this->buscarKey( 'uspass' );
        $usMail = $this->buscarKey( 'usmail' );
        $usDeshabilitado = $this->buscarKey( 'usdeshabilitado' );

        $usuario->setUsnombre( $usNombre );
        $usuario->setUspass( $usPass );
        $usuario->setUsmail( $usMail );
        $usuario->setUsdeshabilitado( $usDeshabilitado );

        $respuesta = $usuario->modificar();
        return $respuesta;
    }


    public function eliminar(){
        $rta = $this->buscarId();
        $response = false;
        if($rta['respuesta']){
            $objUsuario = $rta['obj'];
            $respEliminar = $objUsuario->eliminar();
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
            $objUsuario = $rta['obj'];
            $respEliminar = $objUsuario->Noeliminar();
            if($respEliminar['respuesta']){
                $response = true;
            }
        }else{
            //no encontro el obj
            $response = false;
        }
        return $response;
    }

    public function buscarObjUsuario(){
        $arrayBu = $this->busqueda();
        $objUsuario = new Usuario();
        $rt = $objUsuario->buscar($arrayBu);
        if($rt['respuesta']){
            //lo encontro
            $response = $objUsuario;
        }else{
            //no lo encontro
            $response = false;
        }
        return $response;
    }

    public function buscarObjUsuario2() {
        $usnombre = Data::buscarKey( 'usnombre' );
        $uspass = Data::buscarKey( 'uspass' );
        $arrayBu = [
            'usnombre' => $usnombre,
            'uspass' => $uspass
        ];
        $objUsuario = new Usuario();
        $rta = $objUsuario->buscar( $arrayBu );
        if( $rta['respuesta'] ){
            // Lo encontro
            $response['obj'] = $objUsuario;
            $response['rta'] = true;
        } else {
            // No lo encontro
            $response = false;
        }
        return $response;
    }
    
    public function cargarEnObjeto($nombre, $mail, $pass){
        $usuario = new Usuario();
        $usuario->cargar($nombre, $mail, $pass);
            
    }
}