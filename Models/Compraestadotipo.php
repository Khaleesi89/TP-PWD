<?php
class Compraestadotipo extends db{
    use Condicion;
    //Atributos
    private $idcompraestadotipo;
    private $cetdescripcion;
    private $cetdetalle;
    private $mensajeOp;
    static $mensajeStatic;

    //Constructor
    public function __construct(){
        $this->idcompraestadotipo = '';
        $this->cetdescripcion = '';
        $this->cetdetalle = '';
        $this->mensajeOp = '';
    }

    //Getters y setters
    public function getIdcompraestadotipo(){
        return $this->idcompraestadotipo;
    }
    public function setIdcompraestadotipo($idcompraestadotipo){
        $this->idcompraestadotipo = $idcompraestadotipo;
    }
    public function getCetdescripcion(){
        return $this->cetdescripcion;
    }
    public function setCetdescripcion($cetdescripcion){
        $this->cetdescripcion = $cetdescripcion;
    }
    public function getCetdetalle(){
        return $this->cetdetalle;
    }
    public function setCetdetalle($cetdetalle){
        $this->cetdetalle = $cetdetalle;
    }
    public function getMensajeOp(){
        return $this->mensajeOp;
    }
    public function setMensajeOp($mensajeOp){
        $this->mensajeOp = $mensajeOp;
    }
    public static function getMensajeStatic(){
        return Compraestadotipo::$mensajeStatic;
    }
    public static function setMensajeStatic($mensajeStatic){
        Compraestadotipo::$mensajeStatic = $mensajeStatic;
    }

    public function cargar( $cetdescripcion, $cetdetalle){
        $this->setCetdescripcion($cetdescripcion);
        $this->setCetdetalle($cetdetalle);
    }

    public function buscar($arrayBusqueda){
        $stringBusqueda = $this->SB($arrayBusqueda);
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        //busqueda en si
        $sql = "SELECT * FROM compraestadotipo";
        if($stringBusqueda != ''){
            $sql.= ' WHERE ';
            $sql.= $stringBusqueda;
        }
        $base = new db();
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    if($row2 = $base->Registro()){
                        $this->setIdcompraestadotipo($row2['idcompraestadotipo']);
                        $this->setCetdescripcion($row2['cetdescripcion']);
                        $this->setCetdetalle($row2['cetdetalle']);
                        $respuesta['respuesta'] = true;
                    }
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

    public function insertar(){
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
        $sql = "INSERT INTO compraestadotipo VALUES(DEFAULT, '{$this->getCetdescripcion()}', '{$this->getCetdetalle()}')";
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

    //Antes de usar el modificar se debe utilizar el buscar.
    //En el controlador fijarse si no hay otra tupla con el mismo descripcion
    //En el controlador fijarse si hay un id de compraestadotipo 
    public function modificar(){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
        $sql = "UPDATE compraestadotipo SET cetdescripcion = '{$this->getCetdescripcion()}', cetdetalle = '{$this->getCetdetalle()}' WHERE idcompraestadotipo = {$this->getIdcompraestadotipo()}";
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
    public function eliminar(){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
        $sql = "DELETE FROM compraestadotipo WHERE idcompraestadotipo = {$this->getIdcompraestadotipo()}";
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
        $arregloCompraestadotipo = null;
        $base = new db();
        //seteo de busqueda//ARREGLAR EL CONDICION
        $stringBusqueda = Compraestadotipo::SBS($arrayBusqueda);
        $sql = "SELECT * FROM compraestadotipo";
        if($stringBusqueda != ''){
            $sql.= ' WHERE ';
            $sql.= $stringBusqueda;
        }
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $arregloCompraestadotipo = array();
                    while($row2 = $base->Registro()){
                        $objCompraestadotipo = new Compraestadotipo();
                        $objCompraestadotipo->setIdcompraestadotipo($row2['idcompraestadotipo']);
                        $objCompraestadotipo->setCetdescripcion($row2['cetdescripcion']);
                        $objCompraestadotipo->setCetdetalle($row2['cetdetalle']);
                        array_push($arregloCompraestadotipo, $objCompraestadotipo);
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
            $respuesta['array'] = $arregloCompraestadotipo;
        }
        return $respuesta;
    }

    public function dameDatos(){
        $data = [];
        $data['idcompraestadotipo'] = $this->getIdcompraestadotipo();
        $data['cetdescripcion'] = $this->getCetdescripcion();
        $data['cetdetalle'] = $this->getCetdetalle();
        return $data;
    }
}