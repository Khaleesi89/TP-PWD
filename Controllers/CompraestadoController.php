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
