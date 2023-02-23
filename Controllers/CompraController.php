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

    public function busqueda(){
        $arrayBusqueda = [];
        $idCompra =Data::buscarKey('idcompraestadotipo');
        $cofecha = Data::buscarKey('cofecha');
        $idusuario = Data::buscarKey('idusuario');
        $arrayBusqueda = [
            'idcompra' => $idCompra,
            'cofecha' => $cofecha,
            'idusuario' => $idusuario
        ];
        return $arrayBusqueda;
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


    public function buscarId() {
        $idBusqueda = Data::buscarKey( 'idcompraestadotipo' );
        if( $idBusqueda == false ){
            // Error
            $data['error'] = $this->warning( 'No se ha encontrado dicho registro' );
        } else {
            // Encontrado!
            $array['idcompraestadotipo'] = $idBusqueda;
            $objCompraestadotipo = new Compraestadotipo();
            $rta = $objCompraestadotipo->buscar( $array['idcompraestadotipo'] );
            if( $rta['respuesta'] == false ){
                $data['error'] = $this->manejarError( $rta );
            } else {
                $data['array'] = $objCompraestadotipo;
            }
            return $data;
        }
    }

    public function buscarIdDos(){
        $rta = false;
        $idBusqueda = [];
        $idBusqueda['idcompra'] = Data::buscarKey('idcompra');
        $objCompra = new Compraestadotipo();
        $objEncontrado = $objCompra->buscar($idBusqueda);
        if($objEncontrado['respuesta']){
            $rta['respuesta'] = true;
            $rta['obj'] = $objCompra;
        }
        return $rta;
    }

    public function insertar(){
        $data = $this->busqueda();
        $objCompraestadotipo = new Compraestadotipo();
        $objCompraestadotipo->setIdcompraestadotipo($data['idcompraestadotipo']);
        $objCompraestadotipo->setCetdescripcion($data['cetdescripcion']);
        $objCompraestadotipo->setCetdetalle($data['cetdetalle']);
        $rta = $objCompraestadotipo->insertar();
        return $rta;
    }

    public function modificar(){
        $rta = $this->buscarIdDos();
        $response = false;
        if($rta['respuesta']){
            //puedo modificar con los valores
            $valores = $this->busqueda();
            $objCompraestadotipo = $rta['obj'];
            $objCompraestadotipo->cargar($valores['cetdescripcion'], $valores['cetdetalle']);
            $rsta = $objCompraestadotipo->modificar();
            if($rsta['respuesta']){
                //todo gut
                $response = true;
            }
        }
        return $response;
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

    
    //BUSCA TODAS LAS COMPRAS DE UN USUARIO
    public function buscarCompraSConIdusuario($idusuario){
        $arrBus = [];
        $arrBus['idusuario'] = $idusuario;
        $objCompra = new Compra();
        $rta = $objCompra->listar($arrBus);//tengo todas las compras
        if(!empty($rta['array'])){
            $respuesta = $rta['array'];
        }else{
            $respuesta = false;
        }
        return $respuesta;
    }


    //ACA SACAREMOS LOS ID Y LOS PONEMOS EN UN ARRAY
    public function soloId($arrayComprasDeUsuario){
        if(!empty($arrayComprasDeUsuario)){
            $arrBus = [];
            for ($i=0; $i < count($arrayComprasDeUsuario) ; $i++) { 
                $compra = $arrayComprasDeUsuario[$i];
                $id = $compra->getIdcompra();
                array_push($arrBus,$id);
            }
        }else{
            $arrBus = [];
        }
        return $arrBus;
    }
    


    public function buscarCompraIdusuario($idusuario){
        $arrBus = [];
        $arrBus['idusuario'] = $idusuario;
        return $arrBus;
    }

    //CREA COMPRAESTADO, COMPRAITEM Y COMPRA
    public function crearCompra($idusuario,$idprod,$cantidad){
        $objCompra = new Compra();
        $objUsuario = new Usuario();
        $arrBusUs = [];
        $arrBusUs['idusuario'] = $idusuario;
        $rsa = $objUsuario->buscar($arrBusUs);
        $objCompra->cargar($objUsuario);
        //INSERTO LA COMPRA
        $rta = $objCompra->insertar();
        if($rta['respuesta']){
            //se pudo crear la compra
            $rrrta = $objCompra->ultimaCompraId();
            if($rrrta['respuesta']){
                $quepaso = $objCompra->getIdcompra();
                $objCompraestado = new Compraestado();
                $objCompraestadoTipo = new Compraestadotipo();
                $arrBuCET['idcompraestadotipo'] = 1;
                $objCompraestadoTipo->buscar($arrBuCET);
                $objCompraestado->cargar($objCompra, $objCompraestadoTipo);
                //INSERTO COMPRAESTADO
                $lala = $objCompraestado->insertar();
                if($lala['respuesta']){
                    //INSERTO COMPRAESTADOITEM
                    $objCompraitem = new Compraitem();
                    $producto = new Producto();
                    $buscProd = ['idproducto'];
                    $producto->buscar($buscProd);
                    $objCompraitem->cargar($producto,$objCompra,$cantidad);
                    $chan = $objCompraitem->insertar();
                    if($chan){
                        $quepaso = true;
                    }else{
                        $quepaso = false;
                    }
                }else{
                    $quepaso = false;
                }
            }else{
                $quepaso = false;
            }
        }else{
            $quepaso = false;
        }
        return $quepaso;
    
    }
    

    //COMPRA CON MISMO ID POR ESTAR ACTIVA AUN

    public function compraConCompraIniciada($idcompra,$idusuario,$idprod,$cantidad){
                    //INSERTO COMPRAESTADOITEM
                    $objCompraitem = new Compraitem();
                    //HACE UN CHEQUEO A VER SI YA ESTA ESE PRODUCTO EN COMPRA ITEM ENTONCES SOLO SETEARA LA CANTIDAD A LA ACTUAL
                    //si es el mimso producto o no
                    $esta = $objCompraitem->mismoProducto($idprod,$idcompra);
                    if($esta == ""){
                         //si no es el mimso producto
                        //busco el objeto de producto
                        $producto = new Producto();
                        $buscProd['idproducto'] = $idprod;
                        $producto->buscar($buscProd);
                        //busco el objeto compra
                        $objCompra = new Compra();
                        $buscCompra['idcompra'] = $idcompra;
                        $rta = $objCompra->buscar($buscCompra);
                        $objCompraitem->cargar($producto,$objCompra,$cantidad);
                        $chan = $objCompraitem->insertar();
                        if($chan['respuesta']){
                            $quepaso = true;
                        }else{
                            $quepaso = false;
                        }
                    }else{
                        $objCompraItemExtraido = $esta['array'];
                        $salida = $objCompraitem->cambioStock($cantidad,$objCompraItemExtraido);
                        if($salida){
                            $quepaso = true;
                        }else{
                            $quepaso = false;
                        }
                    }
                    return $quepaso;
        
    }
    



}