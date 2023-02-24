<?php
class Compraestado extends db{	 
	use Condicion;
	//Atributos
	private $idcompraestado;
	private $objCompra;
	private $objCompraestadotipo;
	private $cefechaini;
	private $cefechafin;
	private $mensajeOp;
	static $mensajeStatic;

	//Constructor
	public function __construct(){
		$this->idcompraestado = null;
		$this->objCompra = NULL;
		$this->objCompraestadotipo = NULL;
		$this->cefechaini = null;
		$this->cefechafin = null;
		$this->mensajeOp = '';
	}

	//Metodo cargar
	public function cargar($objCompra, $objCompraestadotipo){
		$this->objCompra = $objCompra;
		$this->objCompraestadotipo = $objCompraestadotipo;
		
	}

	//Getters y setters
	public function getIdcompraestado(){
		return $this->idcompraestado;
	}
	public function setIdcompraestado($idcompraestado){
		$this->idcompraestado = $idcompraestado;
	}
	public function getObjCompra(){
		return $this->objCompra;
	}
	public function setObjCompra($objCompra){
		$this->objCompra = $objCompra;
	}
	public function getObjCompraestadotipo(){
		return $this->objCompraestadotipo;
	}
	public function setObjCompraestadotipo($objCompraestadotipo){
		$this->objCompraestadotipo = $objCompraestadotipo;
	}
	public function getCefechaini(){
		return $this->cefechaini;
	}
	public function setCefechaini($cefechaini){
		$this->cefechaini = $cefechaini;
	}
	public function getCefechafin(){
		return $this->cefechafin;
	}
	public function setCefechafin($cefechafin){
		$this->cefechafin = $cefechafin;
	}
	public function getMensajeOp(){
		return $this->mensajeOp;
	}
	public function setMensajeOp($mensajeOp){
		$this->mensajeOp = $mensajeOp;
	}
	public static function getMensajeStatic(){
		return Compraestado::$mensajeStatic;
	}
	public static function setMensajeStatic($mensajeStatic){
		Compraestado::$mensajeStatic = $mensajeStatic;
	}

	public function buscar($arrayBusqueda){
		//Seteo del array de busqueda, se deberan pasar como claves los campos de la db y como argumentos los parametros a buscar
		$stringBusqueda = $this->SB($arrayBusqueda);
		//Seteo de respuesta
		$respuesta['respuesta'] = false;
		$respuesta['errorInfo'] = '';
		$respuesta['codigoError'] = null;
		//Sql
		$sql = "SELECT * FROM compraestado";
		if($stringBusqueda != ''){
			$sql.= " WHERE $stringBusqueda";
		}
		$base = new db();
		try {
			if($base->Iniciar()){
				if($base->Ejecutar($sql)){
					if($row2 = $base->Registro()){
						$this->setIdcompraestado($row2['idcompraestado']);
						$ids = $row2['idcompra'];
						$objCompra = new Compra();
						$arrayDe['idcompra'] = $ids;
						$objCompra->buscar($arrayDe);
						$this->setObjCompra($objCompra);
						$id = $row2['idcompraestadotipo'];
						$objCompraestadotipo = new Compraestadotipo();
						$arrayDeBusqueda['idcompraestadotipo'] = $id;
						$objCompraestadotipo->buscar($arrayDeBusqueda);
						$this->setObjCompraestadotipo($objCompraestadotipo);
						$this->setCefechaini($row2['cefechaini']);
						$this->setCefechafin($row2['cefechafin']);
						$respuesta['respuesta'] = true;
					}
				}else{
					$this->setMensajeOp($base->getError());
					$respuesta['respuesta'] = false;
					$respuesta['errorInfo'] = 'Hubo un error en la consulta';
					$respuesta['codigoError'] = 1;
				}
			}else{
				$this->setMensajeOp($base->getError());
				$respuesta['respuesta'] = false;
				$respuesta['errorInfo'] = 'Hubo un error con la conexion a la db';
				$respuesta['codigoError'] = 0;
			}
		} catch (\Throwable $th){
			$respuesta['respuesta'] = false;
			$respuesta['errorInfo'] = $th;
			$respuesta['codigoError'] = 3;
		}
		$base = null;
		return $respuesta;
	}

	public function insertar(){
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
		//obtención de idcompra
		$objCompra = $this->getObjCompra();
		$idcompra = $objCompra->getIdcompra();
		//$objCompra = null;
		//obtencion de idcompraestadotipo
		$objCompraestadotipo = $this->getObjCompraestadotipo();
		$idcompraestadotipo = $objCompraestadotipo->getIdcompraestadotipo();
		$objCompraestadotipo = null;
        $sql = "INSERT INTO compraestado VALUES(DEFAULT, $idcompra, $idcompraestadotipo, DEFAULT, DEFAULT)";
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $respuesta['respuesta'] = true;
                }else{
                    $this->setMensajeOp($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1; 
                }
            }else{
                $this->setMensajeOp($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexión de la base de datos';
                $respuesta['codigoError'] = 0; 
            }
        } catch (\Throwable $th) {
            $respuesta['respuesta'] = false;
            $respuesta['errorInfo'] = $th;
            $respuesta['codigoError'] = 3;
        }
        $base = null;
        return $respuesta;
    }

	public function modificar(){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
		//obtención de idcompra
		$objCompra = $this->getObjCompra();
		$idcompra = $objCompra->getIdcompra();
		//$objCompra = null;
		//obtencion de idcompraestadotipo
		$objCompraestadotipo = $this->getObjCompraestadotipo();
		$idcompraestadotipo = $objCompraestadotipo->getIdcompraestadotipo();
		//$objCompraestadotipo = null;
        $sql = "UPDATE compraestado SET idcompra = $idcompra, idcompraestadotipo = $idcompraestadotipo, cefechaini = DEFAULT , cefechafin = CURRENT_TIMESTAMP WHERE idcompraestado = {$this->getIdcompraestado()}";
        //hice el cambio en fecha ini...le puse default porque modificar se usara solamente para modificar la fecha de fin
        
        $base = new db();
        try {
            if( $base->Iniciar() ){
                if( $base->Ejecutar($sql) ){
                    $respuesta['respuesta'] = true;
                } else {
                    $this->setMensajeOp( $base->getError() );
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            } else {
                $this->setMensajeOp( $base->getError() );
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexión de la base de datos';
                $respuesta['codigoError'] = 0;
            }
        } catch( \Throwable $th ){
            $respuesta['respuesta'] = false;
            $respuesta['errorInfo'] = $th;
            $respuesta['codigoError'] = 3;
        }
        $base = null;
        return $respuesta;
    }

	//Usar el buscar antes del eliminar
    //Eliminado logico
    public function eliminar(){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        //obtener fecha
        $sql = "UPDATE compraestado SET cefechafin = CURRENT_TIMESTAMP WHERE idcompraestado = {$this->getIdcompraestado()}";
        $base = new db();
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $respuesta['respuesta'] = true;
                }else{
                    $this->setMensajeOp($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            }else{
                $this->setMensajeOp($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexión de la base de datos';
                $respuesta['codigoError'] = 0;
            }
        } catch (\Throwable $th) {
            $respuesta['respuesta'] = false;
            $respuesta['errorInfo'] = $th;
            $respuesta['codigoError'] = 3;
        }
        $base = null;
        return $respuesta;
    }

	public static function listar($arrayBusqueda){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $arregloCompraestado = null;
        $base = new db();
        //seteo de busqueda//ARREGLAR EL CONDICION
        $stringBusqueda = Compraestado::SBS($arrayBusqueda);
        $sql = "SELECT * FROM compraestado";
        if($stringBusqueda != ''){
            $sql.= ' WHERE ';
            $sql.= $stringBusqueda;
        }
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $arregloCompraestado = array();
                    while($row2 = $base->Registro()){
                        //Modificar
                        $objCompraestado = new Compraestado();
                        $objCompraestado->setIdcompraestado($row2['idcompraestado']);
                        //Generar objeto compra
                        $idcompra = $row2['idcompra'];
                        $objCompra = new Compra();
                        $arrayBus = [];
                        $arrayBus['idcompra'] = $idcompra;
                        $objCompra->buscar($arrayBus);
                        $objCompraestado->setObjCompra($objCompra);
                        $objCompra = null;
                        //Generar objeto compraestadotipo
                        $idcompraestadotipo = $row2['idcompraestadotipo'];
                        $objCompraestadotipo = new Compraestadotipo();
                        $arrayBus = [];
                        $arrayBus['idcompraestadotipo'] = $idcompraestadotipo;
                        $objCompraestadotipo->buscar($arrayBus);
                        $objCompraestado->setObjCompraestadotipo($objCompraestadotipo);
                        $objCompraestadotipo = null;
                        $objCompraestado->setCefechaini($row2['cefechaini']);
                        $objCompraestado->setCefechafin($row2['cefechafin']);

                        array_push($arregloCompraestado, $objCompraestado);
                    }
                    $respuesta['respuesta'] = true;
                }else{
                    Usuario::setMensajeStatic($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            }else{
                Usuario::setMensajeStatic($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexión de la base de datos';
                $respuesta['codigoError'] = 0;
            }
        } catch (\Throwable $th) {
            $respuesta['respuesta'] = false;
            $respuesta['errorInfo'] = $th;
            $respuesta['codigoError'] = 3;
        }
        $base = null;
        if($respuesta['respuesta']){
            $respuesta['array'] = $arregloCompraestado;
        }
        return $respuesta;
    }

    public function dameDatos(){
        $data = [];
        $data['idcompraestado'] = $this->getIdcompraestado();
        //obtencion de idcompra
        $objCompra = $this->getObjCompra();
        $idcompra = $objCompra->getIdcompra();
        $objCompra = null;
        $data['idcompra'] = $idcompra;
        //obtencion de idcompraestadotipo
        $objCompraestadotipo = $this->getObjCompraestadotipo();
        $idcompraestadotipo = $objCompraestadotipo->getIdcompraestadotipo();
        $cetDetalle = $objCompraestadotipo->getCetdetalle();
        $cetDescripcion = $objCompraestadotipo->getCetdescripcion();
        $objCompraestadotipo = null;
        $data['cetdetalle'] = $cetDetalle;
        $data['cetdescripcion'] = $cetDescripcion;
        $data['idcompraestadotipo'] = $idcompraestadotipo;
        $data['cefechaini'] = $this->getCefechaini();
        $data['cefechafin'] = $this->getCefechafin();
        return $data;
    }    
    
    //HACER FUNCION PARA RESTAR LA CANTIDAD DE PRODUCTOS.
    //tengo que traer la compra, el compraitem y producto
    public function cambiarStocksegunEstado($datos){
        $idcompraestado = $datos['idcompraestado'];//[idcompraestado] => 147[idcompra] =>[cefechaini] => [idcompraestadotipo] => 2
        $data = $datos['idcompraestadotipo'];
        $arrayBus['idcompraestado'] = $idcompraestado;
        $rta = $this->buscar($arrayBus);
        //obtengo el obj compra que tiene el objetoY
        $objCompra = $this->getObjCompra();
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


    //para modificar la fecha y modificarla en la base de datos
    public function modificarFechafin($data){
        //buscar por [idcompraestado]
        $arrayBus['idcompraestado'] = $data['idcompraestado'];
        $try = $this->buscar($arrayBus);
        if($try['respuesta']){
            $fechafin = date("Y-m-d H:i:s");
            $this->setCefechafin($fechafin);
            $rta = $this->modificar();
            if($rta['respuesta']){
                $respuesta = true;
            }else{
                $respuesta  = false;
            }
        }else{
            $respuesta = false;
        }
        return $respuesta;   
    }
    


    //FUNCION PARA CREAR EL NUEVO ESTADO ELEGIDO

    public function crearNuevoestadoElegido($data){
        $array = [];
        //tengo objeto compra
        $array ['idcompra'] = $data['idcompra'];
        $objCompra = new Compra();
        $objCompra->buscar($array);
        //tengo objeto compraestadotipo
        $arrayBusquedasT = [];
        $arrayBusquedasT ['idcompraestadotipo'] = $data['idcompraestadotipo'];
        $objCompraestadotipo = new Compraestadotipo();
        $objCompraestadotipo->buscar($arrayBusquedasT);
        //$estado =  $objCompraestadotipo->getCetdescripcion();
        //cargo el nuevo compraestado con el estadotipo nuevo
        $this->cargar($objCompra, $objCompraestadotipo);
        $rta = $this->insertar();
        if($rta){
            $respuesta ['respuesta'] = true;
        }else{
            $respuesta ['respuesta'] = false;
        }
        return $respuesta;
    }


    
        //PARA COMPARAR LOS ID DE LAS COMPRAS CON LAS ID DE LAS COMPRAS EN COMPRA ESTADO
        //ADEMAS NOS FIJAMOS LOS QUE TIENEN FECHAFIN=NULL QUE SON LAS ACTIVAS
        public function sacandoComprasActivas($arrayId){
            $arrayBus = [];
            $respuesta = [];
            $arraySalida = [];
            $totalCompraestad = $this->listar($arrayBus);
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
}