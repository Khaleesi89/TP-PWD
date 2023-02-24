<?php

class CompraestadotipoController extends MasterController{
    use Errores;


    public function listarTodo(){
        $arrayBusqueda = [];
        $arrayTotal = Compraestadotipo::listar($arrayBusqueda);
        if($arrayTotal['respuesta'] == false){
            $array = [];
            
        }else{
            $array = $arrayTotal['array'];
        }
        
        return $array;        
    }



}