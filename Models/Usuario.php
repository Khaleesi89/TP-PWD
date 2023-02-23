<?php
class Usuario extends db{
    use Condicion;
    //Atributos
    private $idusuario;
    private $usnombre;
    private $uspass;
    private $usmail;//revisar en la db el tipo de dato
    private $usdeshabilitado;
    private $mensajeOp;
    static $mensajeStatic;

    //Constructor
    public function __construct(){
        $this->idusuario = '';
        $this->usnombre = '';
        $this->uspass = '';
        $this->usmail = '';
        $this->usdeshabilitado = null;
        $this->mensajeOp = '';
    }

    //Getters y setters
    public function getIdusuario(){
        return $this->idusuario;
    }
    public function setIdusuario($idusuario){
        $this->idusuario = $idusuario;
    }
    public function getUsnombre(){
        return $this->usnombre;
    }
    public function setUsnombre($usnombre){
        $this->usnombre = $usnombre;
    }
    public function getUspass(){
        return $this->uspass;
    }
    public function setUspass($uspass){
        $this->uspass = $uspass;
    }
    public function getUsmail(){
        return $this->usmail;
    }
    public function setUsmail($usmail){
        $this->usmail = $usmail;
    }
    public function getUsdeshabilitado(){
        return $this->usdeshabilitado;
    }
    public function setUsdeshabilitado($usdeshabilitado){
        $this->usdeshabilitado = $usdeshabilitado;
    }
    public function getMensajeOp(){
        return $this->mensajeOp;
    }
    public function setMensajeOp($mensajeOp){
        $this->mensajeOp = $mensajeOp;
    }
    public static function getMensajeStatic(){
        return Usuario::$mensajeStatic;
    }
    public static function setMensajeStatic($mensajeStatic){
        Usuario::$mensajeStatic = $mensajeStatic;
    }

    public function cargar( $usnombre, $uspass, $usmail){
        $this->setUsnombre($usnombre);
        $this->setUspass($uspass);
        $this->setUsmail($usmail);
        
    }

    public function buscar($arrayBusqueda){
        $stringBusqueda = $this->SB($arrayBusqueda);
        
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        //busqueda en si
        $sql = "SELECT * FROM usuario";
        if($stringBusqueda != ''){
            $sql.= ' WHERE ';
            $sql.= $stringBusqueda;
        }
        $base = new db();
        try {
            if($base->Iniciar()){  
                if($base->Ejecutar($sql)){
                    if($row2 = $base->Registro()){
                        $this->setIdusuario($row2['idusuario']);
                        $this->setUsnombre($row2['usnombre']);
                        $this->setUspass($row2['uspass']);
                        $this->setUsmail($row2['usmail']);
                        $this->setUsdeshabilitado($row2['usdeshabilitado']);
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
        $sql = "INSERT INTO usuario VALUES(DEFAULT, '{$this->getUsnombre()}', '{$this->getUspass()}', '{$this->getUsmail()}', NULL)";
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
    //En el controlador fijarse si no hay un usuario con el mismo nombre
    //En el controlador fijarse si hay un id de rol 
    public function modificar(){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
        $sql = "UPDATE usuario SET usnombre = '{$this->getUsnombre()}', uspass = '{$this->getUspass()}', usmail = '{$this->getUsmail()}' WHERE idusuario = {$this->getIdusuario()}";
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
        //obtener fecha actual
        $sql = "UPDATE usuario SET usdeshabilitado = CURRENT_TIMESTAMP WHERE idusuario = {$this->getIdusuario()}";
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
    public function Noeliminar(){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
        //obtener fecha actual
        $sql = "UPDATE usuario SET usdeshabilitado = NULL WHERE idusuario = {$this->getIdusuario()}";
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
        $arregloUsuario = null;
        $base = new db();
        //seteo de busqueda
        $stringBusqueda = Usuario::SBS($arrayBusqueda);
        $sql = "SELECT * FROM usuario";
        if($stringBusqueda != ''){
            $sql.= ' WHERE ';
            $sql.= $stringBusqueda;
        }
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $arregloUsuario = array();
                    while($row2 = $base->Registro()){
                        $objUsuario = new Usuario();
                        $objUsuario->setIdusuario($row2['idusuario']);
                        $objUsuario->setUsnombre($row2['usnombre']);
                        $objUsuario->setUspass($row2['uspass']);
                        $objUsuario->setUsmail($row2['usmail']);
                        $objUsuario->setUsdeshabilitado($row2['usdeshabilitado']);
                        array_push($arregloUsuario, $objUsuario);
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
            $respuesta['array'] = $arregloUsuario;
        }
        return $respuesta;
    }

    public function dameDatos(){
        $data = [];
        $data['idusuario'] = $this->getIdusuario();
        $data['usnombre'] = $this->getUsnombre();
        $data['uspass'] = $this->getUspass();
        $data['usmail'] = $this->getUsmail();
        $data['usdeshabilitado'] = $this->getUsdeshabilitado();
        return $data;
    }
}