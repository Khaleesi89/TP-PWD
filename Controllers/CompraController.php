<?php
class CompraController extends MasterController{
    use Errores;



    //para buscar una compra
    public function buscar($array){
        $compra = new Compra();
        $result = $compra->buscar($array);
        if($result['respuesta']){
            $salida = true;
        }else{
            $salida =  false;
        }
        return $salida;
    
    }

    public function listarTodo($arr){
       
        $arrayTotal = Compra::listar($arr);
        if($arrayTotal['respuesta']){
            $array = $arrayTotal['array'];
        }else{
            $array = [];
        }
        
        return $array;        
    }


    //elimina compra completa seria no solo el compra sino tambien el compraestado y los compraitems
    public function eliminar($data){
        $compra = new Compra();
        $compraEliminar['idcompra'] = $data['idcompra'];
        //BUSCAMOS LA COMPRA
        $resul = $compra->buscar($compraEliminar);
        //BUSCAMOS EL COMPRAESTADO
        $compraestado = new Compraestado();
        $rta = $compraestado->buscar($compraEliminar);
        //BUSCAMOS LOS COMPRAITEMS
        $compraitems = new Compraitem();
        $array = $compraitems::listar($compraEliminar);
        $listaCompraitems = $array['array'];
        foreach ($listaCompraitems as $key => $value) {
            $value->eliminar();
        }
        $seBorro = $compraestado->eliminar();
        $respuesta = $compra->eliminar();
        if($seBorro['respuesta'] && $respuesta['respuesta']){
            $response = true;
        }else{
            $response = false;
        }
        return $response;
    }

    public function buscarCompraIdusuario($idusuario){
        $arrBus = [];
        $arrBus['idusuario'] = $idusuario;
        return $arrBus;
    }



}