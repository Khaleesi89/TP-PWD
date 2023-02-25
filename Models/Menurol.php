<?php
class Menurol extends db{
    use Condicion;
    //Atributos
    private $idmr;
    private $objMenu;
    private $objRol;
    private $mensajeOp;
    static $mensajeStatic;

    //Constructor
    public function __construct(){
        $this->idmr = '';
        $this->objMenu = null;
        $this->objRol = null;
        $this->mensajeOp = '';
    }

    //Getters y setters
    public function getIdmr(){
        return $this->idmr;
    }
    public function setIdmr($idmr){
        $this->idmr = $idmr;
    }
    public function getObjMenu(){
        return $this->objMenu;
    }
    public function setObjMenu($objMenu){
        $this->objMenu = $objMenu;
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
        return Menurol::$mensajeStatic;
    }
    public static function setMensajeStatic($mensajeStatic){
        Menurol::$mensajeStatic = $mensajeStatic;
    }

    public function cargar( $objMenu, $objRol){
        $this->setObjMenu($objMenu);
        $this->setObjRol($objRol);
    }

    public function buscar($arrayBusqueda){
        $stringBusqueda = $this->SB($arrayBusqueda);
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        //busqueda en si
        $sql = "SELECT * FROM menurol";
        if($stringBusqueda != ''){
            $sql.= ' WHERE ';
            $sql.= $stringBusqueda;
        }
        $base = new db();
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    if($row2 = $base->Registro()){
                        $this->setIdmr($row2['idmr']);
                        //Generacion del objeto menu
                        $idMenu = $row2['idmenu'];
                        $objMenu = new Menu();
                        $arrayMenu['idmenu'] = $idMenu;
                        $objMenu->buscar($arrayMenu);
                        $this->setObjMenu($objMenu);
                        $objMenu = null;
                        //Generacion del objeto rol
                        $idRol = $row2['idrol'];
                        $objRol = new Rol();
                        $arrayRol['idrol'] = $idRol;
                        $objRol->buscar($arrayRol);
                        $this->setObjRol($objRol);
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
        //Obtención del idmenu
        $objMenu = $this->getObjMenu();
        $idMenu = $objMenu->getIdmenu();
        $objMenu = null;
        //Obtencion del idrol
        $objRol = $this->getObjRol();
        $idRol = $objRol->getIdrol();
        $objRol = null;
        $base = new db();
        $sql = "INSERT INTO menurol VALUES(DEFAULT, $idMenu, $idRol)";
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
    //En el controlador fijarse si hay un id de menurol 
    public function modificar(){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        //Obtención del idmenu
        $objMenu = $this->getObjMenu();
        $idMenu = $objMenu->getIdmenu();
        
        //Obtencion del idrol
        $objRol = $this->getObjRol();
        $idRol = $objRol->getIdrol();
               
        $sql = "UPDATE menurol SET idmenu = $idMenu, idrol = $idRol WHERE idmr = {$this->getIdmr()}";
        $base = new db();
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $respuesta['respuesta'] = true;
                } else {
                    $this->setMensajeOp( $base->getError() );
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            } else {
                $this->setMensajeOp($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexión de la base de datos';
                $respuesta['codigoError'] = 0;
            }
        } catch( \Throwable $th ){
            $respuesta['respuesta'] = false;
            $respuesta['errorInfo'] = $th;
            $respuesta['codigoError'] = 3;
        }
        
        return $respuesta;
    }

    //Usar el buscar antes del eliminar
    public function eliminar(){
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        //obtener fecha
        $sql = "DELETE FROM menurol WHERE idmr = {$this->getIdmr()}";
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
        $arregloMenuRol = null;
        $base = new db();
        //seteo de busqueda//ARREGLAR EL CONDICION
        $stringBusqueda = Menurol::SBS($arrayBusqueda);
        $sql = "SELECT * FROM menurol";
        if($stringBusqueda != ''){
            $sql.= ' WHERE ';
            $sql.= $stringBusqueda;
        }
        try {
            if($base->Iniciar()){
                if($base->Ejecutar($sql)){
                    $arregloMenuRol = array();
                    while($row2 = $base->Registro()){
                        $objMenuRol = new Menurol();
                        $objMenuRol->setIdmr($row2['idmr']);
                        //Generacion del objeto Menu
                        $objMenu = new Menu();
                        $idMenu = $row2['idmenu'];
                        $arrayMenu['idmenu'] = $idMenu;
                        $objMenu->buscar($arrayMenu);
                        $objMenuRol->setObjMenu($objMenu);
                        $objMenu = null;
                        //Generacion del objeto Rol
                        $objRol = new Rol();
                        $idRol = $row2['idrol'];
                        $arrayRol['idrol'] = $idRol;
                        $objRol->buscar($arrayRol);
                        $objMenuRol->setObjRol($objRol);
                        $objRol = null;
                        array_push($arregloMenuRol, $objMenuRol);
                    }
                    $respuesta['respuesta'] = true;
                }else{
                    Menurol::setMensajeStatic($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            }else{
                Menurol::setMensajeStatic($base->getError());
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
            $respuesta['array'] = $arregloMenuRol;
        }
        return $respuesta;
    }

    public function dameDatos(){
        $data = [];
        $data['idmr'] = $this->getIdmr();
        $objMenu = $this->getObjMenu();
        $data['idmenu'] = $objMenu->getIdmenu();
        $objMenu = null;
        $objRol = $this->getObjRol();
        $data['idrol'] = $objRol->getIdrol();
        $objRol = null;
        return $data;
    }

    public function dameDatosMenues(){
        $data = [];
        $data['idmr'] = $this->getIdmr();
        $objMenu = $this->getObjMenu();
        $datos = $objMenu->dameDatos();
        $data['idmenu'] = $datos;
        $objMenu = null;
        $objRol = $this->getObjRol();
        $data['idrol'] = $objRol->getIdrol();
        $objRol = null;
    
        return $data;
    }


    public function nuevoMenuRol($data,$arrayIdDiferente){
        foreach ($arrayIdDiferente as $key => $value) {//value es idrol
            //tengo el idrol
            $data['idrol'] = $value;
            //creo los objetos
            $menuRol = new Menurol();
            $objRol = new Rol();
            $objMenu = new Menu();
            //voy a buscar esos objetos Rol y Menu
            $arrayBus['idrol'] = $data['idrol'];
            $canDoit = $objRol->buscar($arrayBus);
            $busca['idmenu'] = $data['idmenu'];
            $sePudo = $objMenu->buscar($busca);
            $menuRol->cargar($objMenu,$objRol);
            $respuesta = $menuRol->insertar();
            if($respuesta['respuesta']){
                $salida = true;
            }else{
                $salida = false;
            }
        }
        return $salida;
    }



    public function eliminarMenuRol($data,$arrayIdDiferente){
        foreach ($arrayIdDiferente as $key => $value) {
            $arrayBus['idrol'] = $value;
            $menuRol = new Menurol();
            $arrayBus['idmenu'] = $data['idmenu'];
            $encontro = $menuRol->buscar($arrayBus);
            if($encontro['respuesta']){
                $rta = $menuRol->eliminar();
                if($rta['respuesta']){
                    $salida = true;
                }else{
                    $salida = false;
                }
            }else{
                $salida = false;
            }
        }        
        
        return $salida;
    }



}