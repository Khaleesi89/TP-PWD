<?php
class Compra extends db
{
    use Condicion;
    //Atributos
    private $idcompra;
    private $cofecha;
    private $objUsuario; // se delega el id del usuario
    private $mensajeOp;
    static $mensajeStatic;

    //Constructor
    public function __construct()
    {
        $this->idcompra = '';
        $this->cofecha = '';
        $this->objUsuario = NULL;
        $this->mensajeOp = '';
    }

    //Metodo cargar
    public function cargar($objUsuario)
    {
        $this->objUsuario = $objUsuario;
    }

    //Getters y setters
    public function getIdcompra()
    {
        return $this->idcompra;
    }
    public function setIdcompra($idcompra)
    {
        $this->idcompra = $idcompra;
    }
    public function getCofecha()
    {
        return $this->cofecha;
    }
    public function setCofecha($cofecha)
    {
        $this->cofecha = $cofecha;
    }
    public function getObjUsuario()
    {
        return $this->objUsuario;
    }
    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
    }
    public function getMensajeOp()
    {
        return $this->mensajeOp;
    }
    public function setMensajeOp($mensajeOp)
    {
        $this->mensajeOp = $mensajeOp;
    }
    public static function getMensajeStatic()
    {
        return Compra::$mensajeStatic;
    }
    public static function setMensajeStatic($mensajeStatic)
    {
        Compra::$mensajeStatic = $mensajeStatic;
    }

    public function buscar($arrayBusqueda)
    {

        //Seteo del array de busqueda, se deberan pasar como claves los campos de la db y como argumentos los parametros a buscar
        $stringBusqueda = $this->SB($arrayBusqueda);
        //Seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        //Sql
        $sql = "SELECT * FROM compra";
        if ($stringBusqueda != '') {
            $sql .= " WHERE $stringBusqueda";
        }
        $base = new db();
        

        try {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    if ($row2 = $base->Registro()) {
                        $this->setIdcompra($row2['idcompra']);
                        $this->setCofecha($row2['cofecha']);
                        $id = $row2['idusuario'];
                        $objUsuario = new Usuario();
                        $arrayB['idusuario'] = $id;
                        $objUsuario->buscar($arrayB);
                        $this->setObjUsuario($objUsuario);
                        $respuesta['respuesta'] = true;
                    }
                } else {
                    $this->setMensajeOp($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error en la consulta';
                    $respuesta['codigoError'] = 1;
                }
            } else {
                $this->setMensajeOp($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexion a la db';
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

    public function insertar()
    {
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
        $objusuario = $this->getObjUsuario();
        $idusuario = $objusuario->getIdusuario();
        $sql = "INSERT INTO compra VALUES(DEFAULT, DEFAULT, $idusuario)";
       
        try {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $respuesta['respuesta'] = true;
                } else {
                    $this->setMensajeOp($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            } else {
                $this->setMensajeOp($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexi??n de la base de datos';
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
    public function modificar()
    {
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
        $objusuario = $this->getObjUsuario();
        $idusuario = $objusuario->getIdusuario();
        $sql = "UPDATE compra SET cofecha = DEFAULT, idusuario = $idusuario WHERE idcompra = {$this->getIdcompra()}";
        try {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $respuesta['respuesta'] = true;
                } else {
                    $this->setMensajeOp($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            } else {
                $this->setMensajeOp($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexi??n de la base de datos';
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
    //Eliminacion fisica
    public function eliminar()
    {
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $base = new db();
        $sql = "DELETE FROM compra WHERE idcompra = '{$this->getIdcompra()}'";
        try {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $respuesta['respuesta'] = true;
                } else {
                    $this->setMensajeOp($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            } else {
                $this->setMensajeOp($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexi??n de la base de datos';
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

    public static function listar($arrayBusqueda)
    {
        //seteo de respuesta
        $respuesta['respuesta'] = false;
        $respuesta['errorInfo'] = '';
        $respuesta['codigoError'] = null;
        $arregloCompra = null;
        $base = new db();
        //seteo de busqueda
        $stringBusqueda = Compra::SBS($arrayBusqueda);
        $sql = "SELECT * FROM compra";
        if ($stringBusqueda != '') {
            $sql .= ' WHERE ';
            $sql .= $stringBusqueda;
        }
        try {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $arregloCompra = array();
                    while ($row2 = $base->Registro()) {
                        $idcompra = $row2['idcompra'];
                        $cofecha = $row2['cofecha'];
                        //generacion de objeto usuario
                        $idusuario = $row2['idusuario'];
                        $objUsuario = new Usuario();
                        $arrayBus = [];
                        $arrayBus['idusuario'] = $idusuario;
                        $objUsuario->buscar($arrayBus);
                        $objCompra = new Compra();
                        $objCompra->setObjUsuario($objUsuario);
                        $objCompra->setIdcompra($idcompra);
                        $objCompra->setCofecha($cofecha);
                        $objUsuario = null;
                        array_push($arregloCompra, $objCompra);
                    }
                    $respuesta['respuesta'] = true;
                } else {
                    Usuario::setMensajeStatic($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error con la consulta';
                    $respuesta['codigoError'] = 1;
                }
            } else {
                Usuario::setMensajeStatic($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexi??n de la base de datos';
                $respuesta['codigoError'] = 0;
            }
        } catch (\Throwable $th) {
            $respuesta['respuesta'] = false;
            $respuesta['errorInfo'] = $th;
            $respuesta['codigoError'] = 3;
        }
        $base = null;
        if ($respuesta['respuesta']) {
            $respuesta['array'] = $arregloCompra;
        }
        return $respuesta;
    }

    public function dameDatos()
    {
        $data = [];
        $data['idcompra'] = $this->getIdcompra();
        $data['cofecha'] = $this->getCofecha();
        //obtencion de idusuario
        $objUsuario = $this->getObjUsuario();
        $idusuario = $objUsuario->getIdusuario();
        $objUsuario = null;
        $data['idusuario'] = $idusuario;
        return $data;
    }

    public function ultimaCompraId()
    {
        $sql = "SELECT MAX(idcompra) AS idcompra FROM compra";
        $base = new db();
        try {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    if ($row2 = $base->Registro()) {
                        $this->setIdcompra($row2['idcompra']);
                        $respuesta['respuesta'] = true;
                    }
                } else {
                    $this->setMensajeOp($base->getError());
                    $respuesta['respuesta'] = false;
                    $respuesta['errorInfo'] = 'Hubo un error en la consulta';
                    $respuesta['codigoError'] = 1;
                }
            } else {
                $this->setMensajeOp($base->getError());
                $respuesta['respuesta'] = false;
                $respuesta['errorInfo'] = 'Hubo un error con la conexion a la db';
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


    //BUSCA TODAS LAS COMPRAS DE UN USUARIO
    public function buscarCompraSConIdusuario($idusuario){
        $arrBus = [];
        $arrBus['idusuario'] = $idusuario;
        $rta = $this->listar($arrBus);//tengo todas las compras
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

}
