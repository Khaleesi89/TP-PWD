<?php

trait Data {

    private static function data(){
        $datos = [];
        if( isset($_GET) && count($_GET) >= 0 ){
            $datosGet = [];
            foreach ($_GET as $key => $value) {
                if( $value != null || $value != '' ){
                    $datosGet["$key"] = $value;
                }else{
                    $datosGet["$key"] = 'void';
                }
            }
        }

        if( isset($_POST) && count($_POST) >= 0 ){
            $datosPost = [];
            foreach( $_POST as $key => $value ){
                if( $value != null || $value != '' ){
                    $datosPost["$key"] = $value;
                }else{
                    $datosPost["$key"] = 'void';
                }
            }
        }

        $datos['GET'] = $datosGet;
        $datos['POST'] = $datosPost;
        return( $datos );
    }

    
    // Traigo datos de get/post
    public static function getDatos(){
        $datos = Data::data();
        return $datos;
    }


    //Funcion para buscar la key
    public static function buscarKey( $key ){
        
        $datos = Data::getDatos();
        $post = $datos['POST'];
        $get = $datos['GET'];
        if( array_key_exists($key, $post) ){
            $respuesta = $post[$key];
        } elseif( array_key_exists($key, $get) ){
            $respuesta = $get[$key];
        } else {
            $respuesta = false;
        }
        return $respuesta;
    }
}
