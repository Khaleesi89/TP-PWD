<?php
//require_once('../config.php');
class Menu extends db{
    use Condicion;
    //Atributos
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $objPadre;
    private $medeshabilitado;
    private $mensajeOp;
    static $mensajeStatic;

    //Constructor
    public function __construct(){
        $this->idmenu = '';
        $this->menombre = '';
        $this->medescripcion = '';
        $this->objPadre = null;
        $this->medeshabilitado = null;
        $this->mensajeOp = '';
    }

    //Getters y setters
    public function getIdmenu(){
        return $this->idmenu;
    }
    public function setIdmenu($idmenu){
        $this->idmenu = $idmenu;
    }
    public function getMenombre(){
        return $this->menombre;
    }
    public function setMenombre($menombre){
        $this->menombre = $menombre;
    }
    public function getMedescripcion(){
        return $this->medescripcion;
    }
    public function setMedescripcion($medescripcion){
        $this->medescripcion = $medescripcion;
    }
    public function getObjPadre(){
        return $this->objPadre;
    }
    public function setObjPadre($objPadre){
        $this->objPadre = $objPadre;
    }
    public function getMedeshabilitado(){
        return $this->medeshabilitado;
    }
    public function setMedeshabilitado($medeshabilitado){
        $this->medeshabilitado = $medeshabilitado;
    }
    public function getMensajeOp(){
        return $this->mensajeOp;
    }
    public function setMensajeOp($mensajeOp){
        $this->mensajeOp = $mensajeOp;
    }
    public static function getMensajeStatic(){
        return Menu::$mensajeStatic;
    }
    public static function setMensajeStatic($mensajeStatic){
        Menu::$mensajeStatic = $mensajeStatic;
    }

    public function getIdpadre(){
        $objPadre = $this->getObjPadre();
        try {
            $idPadre = $objPadre->getIdmenu();
        } catch (\Throwable $th) {
            $idPadre = 0;
        }
        return $idPadre;
    }

    public function cargar($menombre, $medescripcion, $objPadre){
        $this->setMenombre($menombre);
        $this->setMedescripcion($medescripcion);
        $this->setObjPadre($objPadre);
        
    }

    public function buscar($arrayBusqueda){
        $stringBusqueda = $this->SB($arrayBusqueda);
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        //busqueda en si
        $sql = "SELECT * FROM menu";
        if($stringBusqueda != ''){
            $sql.= ' WHERE ';
            $sql.= $stringBusqueda;
        }
        $base = new db();
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    if($row2 = $base->Registro()){
                        $this->setIdmenu($row2['idmenu']);
                        $this->setMenombre($row2['menombre']);
                        $this->setMedescripcion($row2['medescripcion']);
                        $this->setMedeshabilitado($row2['medeshabilitado']);
                        $idPadre = $row2['idpadre'];
                        $objMenu = new Menu();
                        if($idPadre == 0 || $idPadre == null){
                            $objMenu = null;
                        }else{
                            $arrayId['idmenu'] = $idPadre;
                            $objMenu->buscar($arrayId);
                        }
                        $this->setObjPadre($objMenu);
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
        $objPadre = $this->getObjPadre();
        if($objPadre == null){
            $idpadre = 0;
        }else{
            $idpadre = $objPadre->getIdmenu();
        }
        if($idpadre == null){
            $idpadre = 0;
        }
        $objPadre = null;
        $base = new db();
        $sql = "INSERT INTO menu VALUES(DEFAULT, '{$this->getMenombre()}', '{$this->getMedescripcion()}', $idpadre, DEFAULT)";
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
        $objPadre = $this->getObjPadre();
        try {
            $idPadre = $objPadre->getIdmenu();
        } catch (\Throwable $th) {
            $idPadre = 0;
        }
        $objPadre = null;
        $sql = "UPDATE menu SET menombre = '{$this->getMenombre()}', medescripcion = '{$this->getMedescripcion()}', idpadre = $idPadre, medeshabilitado = NULL  WHERE idmenu = {$this->getIdmenu()}";
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
        $sql = "UPDATE menu SET medeshabilitado = CURRENT_TIMESTAMP WHERE idmenu = {$this->getIdmenu()}";
        
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

    //Usar el buscar antes del eliminar
    //Eliminado logico
    public function Noeliminar(){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        //obtener fecha
        $sql = "UPDATE menu SET medeshabilitado = NULL WHERE idmenu = {$this->getIdmenu()}";
        
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
        $arregloMenu = null;
        $base = new db();
        //seteo de busqueda//ARREGLAR EL CONDICION
        $stringBusqueda = Menu::SBS($arrayBusqueda);
        if(array_key_exists('sql', $arrayBusqueda)){
            $sql = $arrayBusqueda['sql'];
        }else{
            $sql = "SELECT * FROM menu";
            if($stringBusqueda != ''){
                $sql.= ' WHERE ';
                $sql.= $stringBusqueda;
            }
        }
        
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $arregloMenu = array();
                    while($row2 = $base->Registro()){
                        $objMenu = new Menu();
                        $objMenu->setIdmenu($row2['idmenu']);
                        $objMenu->setMenombre($row2['menombre']);
                        if(array_key_exists('medescripcion', $row2)){
                            $objMenu->setMedescripcion($row2['medescripcion']);
                        }
                        
                        $objMenuPadre = new Menu();
                        if(array_key_exists('idpadre', $row2)){
                            $idPadre = $row2['idpadre'];
                            if($idPadre == 0 || $idPadre == null){
                                $objMenuPadre = null;
                            }else{
                                $arrayPadre['idmenu'] = $idPadre;
                                $objMenuPadre->buscar($arrayPadre);
                            }
                            $objMenu->setObjPadre($objMenuPadre);
                        }
                        if(array_key_exists('medeshabilitado', $row2)){
                            $objMenu->setMedeshabilitado($row2['medeshabilitado']);
                        }
                        array_push($arregloMenu, $objMenu);
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
            $respuesta['array'] = $arregloMenu;
        }
        return $respuesta;
    }

        //hacen lo mismo el dameDatos y el dameDatosRecursivo
    public function dameDatosRecursivo(){
        $data = [];
        $data['idmenu'] = $this->getIdmenu();
        $data['menombre'] = $this->getMenombre();
        $data['medescripcion'] = $this->getMedescripcion();
        $objPadre = $this->getObjPadre();
        try {
            $datosPadre = $objPadre->dameDatosRecursivo();
        } catch (\Throwable $th) {
            $datosPadre = '0';
        }
        $data['idpadre'] = $datosPadre;
        $data['medeshabilitado'] = $this->getMedeshabilitado();
        return $data;
    }

    //hacen lo mismo el dameDatos y el dameDatosRecursivo
    public function dameDatos(){
        $data = [];
        $data['idmenu'] = $this->getIdmenu();
        $data['menombre'] = $this->getMenombre();
        $data['medescripcion'] = $this->getMedescripcion();
        $objPadre = $this->getObjPadre();
        if($objPadre == null){
            $idpadre = 0;
        }else{
            $idpadre = $objPadre->getIdmenu();
        }
        
        if($idpadre == null){
            $idpadre = 0;
        }
        $data['idpadre'] = $idpadre;
        $data['medeshabilitado'] = $this->getMedeshabilitado();
        return $data;
    }

    public static function darMenuesSinMenu($idmenu = NULL){
        $condicion = '';
        $sql = "SELECT * FROM menu ";
        if($idmenu != NULL){
            $condicion = " WHERE idmenu != $idmenu AND medeshabilitado IS NULL";
        }
        if($condicion != ''){
            $sql .= $condicion;
        }
        $arrayBus['sql'] = $sql;
        $arrayTotal = Menu::listar($arrayBus);
        if(array_key_exists('array', $arrayTotal)){
            $devuelve = $arrayTotal['array'];
        }else{
            $devuelve = [];
        }
        return $devuelve;
    }
}