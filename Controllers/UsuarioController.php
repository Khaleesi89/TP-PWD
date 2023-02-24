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
    
    public function cargarEnObjeto($nombre, $mail, $pass){
        $usuario = new Usuario();
        $usuario->cargar($nombre, $mail, $pass);
            
    }
}