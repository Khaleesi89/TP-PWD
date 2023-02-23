<?php
class Usuariorol extends db {
    use Condicion;
    //Atributos
    private $idur;
    private $objUsuario;
    private $objRol;
    private $mensajeOp;
    static $mensajeStatic;

    //Constructor
    public function __construct(){
        $this->idur = '';
        $this->objUsuario = null;
        $this->objRol = null;
        $this->mensajeOp = '';
    }

    //Getters y setters
    public function getIdur(){
        return $this->idur;
    }
    public function setIdur($idur){
        $this->idur = $idur;
    }
    public function getObjUsuario(){
        return $this->objUsuario;
    }
    public function setObjUsuario($objUsuario){
        $this->objUsuario = $objUsuario;
    }
    public function getObjRol(){
        return $this->objRol;
    }
    public function setObjRol($objRol){
        $this->objRol = $objRol;
    }
    public function getMensajeOp(){
        return $this->mensajeOp;
    }
    public function setMensajeOp($mensajeOp){
        $this->mensajeOp = $mensajeOp;
    }
    public static function getMensajeStatic(){
        return Usuariorol::$mensajeStatic;
    }
    public static function setMensajeStatic($mensajeStatic){
        Usuariorol::$mensajeStatic = $mensajeStatic;
    }

    public function cargar( $objUsuario, $objRol){
        $this->setObjUsuario($objUsuario);
        $this->setObjRol($objRol);
    }

    //MODIFICAR EL SETEARBUSQUEDATATATATA PA QUE BUSQUE POR ID DE USUARIO O ID DE ROL
    public function buscar($arrayBusqueda){
        $stringBusqueda = $this->SB($arrayBusqueda);
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        //busqueda en si
        $sql = "SELECT * FROM usuariorol";
        if($stringBusqueda != ''){
            $sql.= ' WHERE ';
            $sql.= $stringBusqueda;
        }
        $base = new db();
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    if($row2 = $base->Registro()){
                        $this->setIdur($row2['idur']);
                        //generar objeto de usuario
                        $objUsuario = new Usuario();
                        $idUsuario = $row2['idusuario'];
                        $arrayUsuario['idusuario'] = $idUsuario;
                        $objUsuario->buscar($arrayUsuario);
                        $this->setObjUsuario($objUsuario);
                        $objUsuario = null;
                        //generar objeto de rol
                        $objRol = new Rol();
                        $idRol = $row2['idrol'];
                        $arrayRol['idrol'] = $idRol;
                        $objRol->buscar($arrayRol);
                        $this->setObjRol($objRol);
                        $objRol = null;
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
        //obtencion de id de usuario
        $objUsuario = $this->getObjUsuario();
        $idUsuario = $objUsuario->getIdusuario();
        $objUsuario = null;
        //obtencion de id de rol
        $objRol = $this->getObjRol();
        $idRol = $objRol->getIdrol();
        $objRol = null;
        $sql = "INSERT INTO usuariorol VALUES(DEFAULT, $idUsuario, $idRol)";
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
        //obtencion de id de usuario
        $objUsuario = $this->getObjUsuario();
        $idUsuario = $objUsuario->getIdusuario();
        $objUsuario = null;
        //obtencion de id de rol
        $objRol = $this->getObjRol();
        $idRol = $objRol->getIdrol();
        $objRol = null;
        $sql = "UPDATE usuariorol SET idusuario = $idUsuario, idrol = $idRol WHERE idur = {$this->getIdur()}";
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
    //borrado de tupla fisico
    public function eliminar(){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
        $sql = "DELETE FROM usuariorol WHERE idur = {$this->getIdur()}";
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
        $arregloUsuariorol = null;
        $base = new db();
        //seteo de busqueda
        $stringBusqueda = Usuario::SBS($arrayBusqueda);
        $sql = "SELECT * FROM usuariorol";
        if($stringBusqueda != ''){
            $sql.= ' WHERE ';
            $sql.= $stringBusqueda;
        }
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $arregloUsuariorol = array();
                    while($row2 = $base->Registro()){
                        $objUsuariorol = new Usuariorol();
                        $objUsuariorol->setIdur($row2['idur']);
                        //generacion de objeto de usuario
                        $arrayUsuario['idusuario'] = $row2['idusuario'];
                        $objUsuario = new Usuario();
                        $objUsuario->buscar($arrayUsuario);
                        $objUsuariorol->setObjUsuario($objUsuario);
                        $objUsuario = null;
                        //generacion de objeto de rol
                        $arrayRol['idrol'] = $row2['idrol'];
                        $objRol = new Rol();
                        $objRol->buscar($arrayRol);
                        $objUsuariorol->setObjRol($objRol);
                        $objRol = null;
                        array_push($arregloUsuariorol, $objUsuariorol);
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
            $respuesta['array'] = $arregloUsuariorol;
        }
        return $respuesta;
    }

    public function dameDatos(){
        $data = [];
        $data['idur'] = $this->getIdur();
        $objUsuario = $this->getObjUsuario();
        $data['idusuario'] = $objUsuario->getIdusuario();
        $objUsuario = null;
        $objRol = $this->getObjRol();
        $data['idrol'] = $objRol->getIdrol();
        $objRol = null;
        return $data;
    }
}