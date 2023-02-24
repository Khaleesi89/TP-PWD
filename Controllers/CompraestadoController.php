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


    //para modificar la fecha y modificarla en la base de datos
    public function modificarFechafin(){
        $arrayCompraestado = $this->buscarId();
        $objCompraestado = $arrayCompraestado['obj'];
        $fechafin = date("Y-m-d H:i:s");
        $objCompraestado->setCefechafin($fechafin);
        $rta = $objCompraestado->modificar();
        if($rta['respuesta']){
            $respuesta = true;
        }else{
            $respuesta  = false;
        }
        return $respuesta;
        
        
    }

    public function crearNuevoestadoElegido($data){
        $array = [];
        $objCompraestado = new Compraestado();
        //tengo objeto compra
        $array ['idcompra'] = $data['idcompra'];
        $objCompra = new Compra();
        $objCompra->buscar($array);
        //tengo objeto compraestadotipo
        $arrayBusquedasT = [];
        $arrayBusquedasT ['idcompraestadotipo'] = $data['idcompraestadotipo'];
        $objCompraestadotipo = new Compraestadotipo();
        $objCompraestadotipo->buscar($arrayBusquedasT);
        $estado =  $objCompraestadotipo->getCetdescripcion();
        //cargo el nuevo compraestado con el estadotipo nuevo
        $objCompraestado->cargar($objCompra, $objCompraestadotipo);
        $rta = $objCompraestado->insertar();
        if($rta){
            $respuesta ['respuesta'] = true;
        }else{
            $respuesta ['respuesta'] = false;
        }
        
        return $respuesta;
    }
    

    //HACER FUNCION PARA RESTAR LA CANTIDAD DE PRODUCTOS.
    //tengo que traer la compra, el compraitem y producto
    public function cambiarStocksegunEstado($datos){
        $idcompraestado = $datos['idcompraestado'];//[idcompraestado] => 147[idcompra] =>[cefechaini] => [idcompraestadotipo] => 2
        $data = $datos['idcompraestadotipo'];
        $arrayBus['idcompraestado'] = $idcompraestado;
        $objCompraestado = new CompraEstado();
        $rta = $objCompraestado->buscar($arrayBus);
        //obtengo el obj compra que tiene el objetoY
        $objCompra = $objCompraestado->getObjCompra();
        //obtengo el obj estadotipo que tiene sin el cambio
        //obtengo el id de la compra
        $idCompra = $objCompra->getIdcompra();
        //hacemos bandera
        $respuesta = [];                
        //creo un array para realizar la bsuqueda de eso en el parametro en compraitem
        $array = [];
        $array['idcompra'] = $idCompra;
        $arraycompraitem = Compraitem::listar($array);
        if(array_key_exists('array', $arraycompraitem)){
            $listaCompraitem = $arraycompraitem['array'];
            foreach ($listaCompraitem as $key => $value) {
                $objCompraitem = $value;
                $cantidadComprada = $objCompraitem->getCicantidad();
                $producto = $objCompraitem->getObjProducto();
                $cantidadtotal = $producto->getProCantStock();
                if($data == "2" || $data == 2){
                    if($cantidadtotal > $cantidadComprada){
                        $totalyn = $cantidadtotal - $cantidadComprada;
                        $producto->setProCantStock($totalyn);
                        $producto->modificar();
                        $mensaje = "Su stock es suficiente, puede realizar la compra";
                        $respuesta['mensaje'] = $mensaje;
                        $respuesta['respuesta'] = true;
                        
                    }else{
                        $mensaje = "Tiene stock insuficiente";
                        $respuesta['mensaje'] = $mensaje;
                        $respuesta['respuesta'] = false;
                        
                    }
                }elseif ($data == 4 || $data == "4") {
                    //hacer que vuelva a sumar el stock
                    $totalito = $cantidadtotal + $cantidadComprada;
                    $producto->setProCantStock($totalito);
                    $producto->modificar();
                    $respuesta['respuesta'] = true;
                }elseif ($data == 3 || $data == "3") {
                        //se deja igual el stock pero se envia true para que siga el proceso
                        $respuesta['respuesta'] = true;
                }else{
                    $mensaje = "Debe cambiar el estado tipo";
                    $respuesta['mensaje'] = $mensaje;
                    $respuesta['respuesta'] = false;
                }    
            } 
        }else{
            $respuesta['respuesta'] = false;
            $respuesta['mensaje'] = "No existen items en su compra";  
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

}
