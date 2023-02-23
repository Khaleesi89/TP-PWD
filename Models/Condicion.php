<?php
//HAY QUE CAMBIARLO
trait Condicion{

    //Metodo publico general
    public function SB($arrayBusqueda){
        $stringBusqueda = '';
        if(count($arrayBusqueda) > 0){
            foreach ($arrayBusqueda as $key => $value) {
                if($value != null || $key == 'usdeshabilitado' || $key == 'prdeshabilitado' || $key == 'medeshabilitado' || $key == 'cefechafin'){
                    if($key == 'usdeshabilitado' || $key == 'prdeshabilitado' || $key == 'medeshabilitado' || $key == 'cefechafin'){
                        $string = " $key IS NULL ";
                    }else{
                        $string = " $key = '$value' ";
                    }
                    if($stringBusqueda == ''){
                        $stringBusqueda.=$string;
                    }else{
                        $stringBusqueda.= ' and ';
                        $stringBusqueda.= $string;
                    }
                }
            }
        }        
        return $stringBusqueda;
    }

    //Metodo static general
    public static function SBS( $arrayBusqueda ){
        $stringBusqueda = '';
        if( is_countable($arrayBusqueda) > 0 ){
            if(!array_key_exists('sql', $arrayBusqueda)){
                foreach ($arrayBusqueda as $key => $value) {
                    if(($value != null && $value != '') || $key == 'usdeshabilitado' || $key == 'prdeshabilitado' || $key == 'medeshabilitado' || $key == 'cefechafin'){
                        if($key == 'usdeshabilitado' || $key == 'prdeshabilitado' || $key == 'medeshabilitado' || $key == 'cefechafin'){
                            $string = " $key IS NULL ";
                        }else{
                            $string = " $key = '$value' ";
                        }
                        if($stringBusqueda == ''){
                            $stringBusqueda.=$string;
                        }else{
                            $stringBusqueda.= ' and ';
                            $stringBusqueda.= $string;
                        }
                    }  
                }
            }else{
                $stringBusqueda = $arrayBusqueda['sql'];
            }
            
        }        
        return $stringBusqueda;
    }
    
}