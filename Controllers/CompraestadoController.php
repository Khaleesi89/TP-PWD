<?php


class CompraestadoController extends MasterController {
    use Errores;


    public function listarTodo($arrayBusqueda){
        $arrayTotal = Compraestado::listar($arrayBusqueda);
        if(array_key_exists('array', $arrayTotal)){
            $array = $arrayTotal['array'];
        }else{
            $array = [];
        }
        
        return $array;        
    }


    public function insertarCompraEstadoNueva($idcompra){
        $objCompraEstado = new Compraestado();
        //generar objeto de compraestadotipo
        $arrBusCET['idcompraestadotipo'] = 1;
        $objCompraEstadoTipo = new Compraestadotipo();
        $objCompraEstadoTipo->buscar($arrBusCET);
        //generar objeto de compra 
        $arrBusC['idcompra'] = $idcompra;
        $objCompra = new Compra();
        $objCompra->buscar($arrBusC);
        $objCompraEstado->cargar($objCompra, $objCompraEstadoTipo);
        $rsta = $objCompraEstado->insertar();
        if($rsta['respuesta']){
            $response = true;
        }else{
            $response = false;
        }
        return $response;
    }


    //BUSCAR 

    public function buscar($data){
        $compraestado = new Compraestado();
        $rta = $compraestado->buscar($data);
        if($rta['respuesta']){
            $respo = $compraestado;
        }else{
            $respo = false;
        }
        return $respo;
    }


    //SACA LA PRIMERA COMPRA QUE TIENE ESTADO 1 Y NULL
    //NO DISTINGUE USUARIOS
    
    public function obtenerCompraActivaPorId($idcompraactiva){
        
        $arrBus = [];
        $arrBus['idcompra'] = $idcompraactiva;
        $arrBus['idcompraestadotipo'] = 1;
        $arrBus['cefechafin'] = NULL;
        $objCompraEstado = new Compraestado();
        $rta = $objCompraEstado->buscar($arrBus);
        $respuesta = false;
        if($rta['respuesta']){
                $respuesta = $objCompraEstado;
        }
        return $respuesta;
    
    } 

    public function modificarEstado($idcompraestado, $idcompraestadotipo){
        $objCompraEstado = new Compraestado();
        $arrBusCompraEstado['idcompraestado'] = $idcompraestado;
        $rta = $objCompraEstado->buscar($arrBusCompraEstado);
        if($rta['respuesta']){
            //cambio de estado
            $objCompraestadotipo = new Compraestadotipo();
            $arrB['idcompraestadotipo'] = $idcompraestadotipo;
            $objCompraestadotipo->buscar($arrB);
            $objCompraEstado->setObjCompraestadotipo($objCompraestadotipo);
            //$objCompraParaModifcar->modificarFechafin();
            $bandera = $objCompraEstado->insertar();
            if($bandera['respuesta']){
                $respuesta = true;
            }else{
                $respuesta = false;
            }
        }else{
            $respuesta = false;
        }
        return $respuesta;
    }

   
        //PARA COMPARAR LOS ID DE LAS COMPRAS CON LAS ID DE LAS COMPRAS EN COMPRA ESTADO
        //ADEMAS NOS FIJAMOS LOS QUE TIENEN FECHAFIN=NULL QUE SON LAS ACTIVAS
        public function sacandoComprasActivas($arrayId){
            $compraestado = new Compraestado();
            $arrayBus = [];
            $respuesta = [];
            $arraySalida = [];
            $totalCompraestad = $compraestado->listar($arrayBus);
            if($totalCompraestad['array']){
                $listadocompleto = $totalCompraestad['array'];
                for ($i=0; $i < count($listadocompleto); $i++) { 
                    $compraestado = $listadocompleto[$i];
                    $id = $compraestado->getObjCompra()->getIdcompra();
                    //VERIFICO SI ESE ID ESTA EN EL ARRY DE COMPRAS DEL USUARIO
                    if(in_array($id,$arrayId)){
                        if($compraestado->getCefechafin() == NULL){
                            //SAQUE LAS QUE TIENEN FECHA NULL PERO ESTAN CANCELADAS
                            if($compraestado->getObjCompraestadotipo()->getIdcompraestadotipo() != 4){
                                array_push($arraySalida,$compraestado);
                                $mensaje = "Aquí sus compras activas";
                                $respuesta['mensaje'] = $mensaje;
                                $respuesta['respuesta'] = true;
                                $respuesta['array'] = $arraySalida;
                            }
                        }
                    }
                }
            }else{
                $mensaje = "No tiene compras activas";
                $respuesta['mensaje'] = $mensaje;
                $respuesta['respuesta'] = false;
            }
            
            return $respuesta;
        }


        //LA QUE NO FUNCIONO HAY QUE VER PORQUE 

        public function soloEstadoInicial($comprasactivas){
            //compras activas trae todas las compras con fecha fin null
            if(empty($comprasactivas)){
                $idcompraIniciada = false;
            }else{
                $bandera = true;
                $i = 0;
                while ($bandera && $i < count(($comprasactivas))){
                    $compraestado = $comprasactivas[$i];
                    $idCompraEstado = $compraestado->getObjCompraestadotipo()->getIdcompraestadotipo();
                    if($idCompraEstado == 1 ||$idCompraEstado == '1' ){
                        $idcompraIniciada = $compraestado->getObjCompra()->getIdcompra();
                        $bandera = false;
                    }else{
                        $idcompraIniciada = false;
                        $i++;
                    }
                    
                }
            }
            return $idcompraIniciada;
        }

        //EXTRAEMOS DEL ARRAY DE LAS COMPRAS CON FECHAFIN NULL SOLOO 
        //NOS QUEDAMOS CON LAS INICIADAS

        public function soloLasIniciadas($array){
            $comprasiniciadas = [];
            for ($i=0; $i < count(($array)) ; $i++) { 
                $compraestado = $array[$i];
                $idCompraEstado = $compraestado->getObjCompraestadotipo()->getIdcompraestadotipo();
                if($idCompraEstado == 1 ||$idCompraEstado == '1' ){
                    $compraIniciada = $compraestado->getObjCompra();
                    array_push($comprasiniciadas,$compraIniciada);
                }
            }
            return $comprasiniciadas;
        } 
    
        //hace depuracion de el listado entero de las compraestado
        public function depuracion($array){
            $arrayFinal = [];
            for ($i=0; $i < count($array) ; $i++) { 
                $compritaEstado = $array[$i];//una compraestado
                if($compritaEstado->getCefechafin() == null){
                    $objcompraestadotipo = $compritaEstado->getObjCompraestadotipo();
                    if($objcompraestadotipo->getIdcompraestadotipo() == 1 || $objcompraestadotipo->getIdcompraestadotipo() == 2){
                        array_push($arrayFinal,$compritaEstado);
                    } 
                }
                
            }
            
            return $arrayFinal;            
        
        } 

        public function modificacion($data){
            $objCompraestado = new Compraestado();
            //esto lo usaremos para personalizar el body del mail
            $estadotipo = new Compraestadotipo();
            $arrayB['idcompraestadotipo'] = $data['idcompraestadotipo'];
            $tim = $estadotipo->buscar($arrayB);
            $detalledeEstado = $estadotipo->getCetdescripcion();
            //comprobamos que la cantidad de stock este disponible
            $haystockDisponible = $objCompraestado->cambiarStocksegunEstado($data);
            if($haystockDisponible['respuesta']){
                //si la cantidad de stock esta disponible entonces hacemos el seteo de la fecha
                $rta = $objCompraestado->modificarFechafin($data);
                if($rta){
                    //y hacemos la nueva tupla con la info nueva
                    $respuestita = $objCompraestado->crearNuevoestadoElegido($data);
                    if($respuestita['respuesta']){
                            $mensaje = "Se ha realizado el cambio de estado";
                            $rtaS = true;
                    }else{
                        $mensaje = "No se ha podido realizar la operación";
                        $rtaS = false;
                    }
                }else{
                    $mensaje = "No se pudo modificar la fecha";
                    $rtaS = false;
                }
            }else{
                $mensaje = "No hay stock disponible";
                $rtaS = false;
            }
            $retorno['respuesta'] = $rtaS;
            if(isset($mensaje)){
                    $retorno['errorMsg'] = $mensaje;
            }
            return$retorno;
        }
}
