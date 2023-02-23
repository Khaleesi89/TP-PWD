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
    

    
}