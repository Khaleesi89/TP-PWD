<?php
require_once('../../../config.php');
$objCompraItemCon = new CompraitemController();
$objProCon = new ProductoController();
$objSession = new SessionController();

$obtenerURL = explode('/', $_SERVER['REQUEST_URI']);
$obtenerURL = array_reverse($obtenerURL);
$url = explode("=",$obtenerURL[0]);
$idprod = $url[1];
$data = data_submitted();
$data['idproducto'] = $idprod;//la data tiene  las siguientes keys
//pronombre,sinopsis,procantstock,autor,precio,isbn,categoria,cicantidad,idproducto
$cicantidad = $data['cicantidad'];
$cantStock = $data['procantstock'];
//Comprobar stock

if ($cicantidad <= $cantStock) {
    $validStock = true;
    //buscar si hay una compra iniciada
    $idusuario = $objSession->getIdusuario();//obtengo id usuario
    //obtener compra con idusuario
    $objCompraCon = new CompraController();
    $ComprUsuario = $objCompraCon->buscarCompraSConIdusuario($idusuario);
    
    
    //true si esta vacio y false si no
    if(!empty($ComprUsuario)){
        //ENTRA ACA SI TIENE COMPRAS iniciadas o finalizadas
        //obtener solo los id
        $arrayconLosId = $objCompraCon->soloId($ComprUsuario);
        $objCompraestadoCon = new CompraestadoController();
        //VAN LAS COMPRAS QUE TIENEN FECHAFIN = NULL
        $sacandolsComprasActivas = $objCompraestadoCon->sacandoComprasActivas($arrayconLosId);
        
        //ESTO ES TRUE -> HAY COMPRAS DEL USUARIO ANTERIORMENTE
        if($sacandolsComprasActivas['respuesta']){
            $comprAct = $sacandolsComprasActivas['array'];
            //AHORA TENGO QUE SACAR LAS QUE TIENEN SOLO COMPRAESTADO1 pero SOLO DEVUELVE 1
            //devuelve el id de la compra en estado 1 y es true si no hay compras activas
            $unaCompraestadoinicial = $objCompraestadoCon->soloEstadoInicial($comprAct);
            
            if($unaCompraestadoinicial){
                //COMPRA CON ID EN ESTADO INICIADA ENTONCES SOLO HACE EL COMPRAITEM
                
                $resp = $objCompraCon->compraConCompraIniciada($unaCompraestadoinicial,$idusuario,$idprod,$cicantidad);
                if($resp){
                    $mensaje = "su compra se realizó correctamente";
                    $respuesta = true;
                }else{
                    $mensaje = "Hay un error en su compra";
                    $respuesta = false;
                }
                $retorno = [];
                $retorno['respuesta'] = $respuesta;
                if (isset($mensaje)) {
                    $retorno['errorMsg'] = $mensaje;
                }
            }else{ 
                //SIN COMPRA EN ESTADO INICIADA ENTONCES CREA UNA NUEVA COMPRA
                $resp = $objCompraCon->crearCompra($idusuario,$idprod,$cicantidad);
                if($resp){
                    $mensaje = "su compra se realizó correctamente";
                    $respuesta = true;
                }else{
                    $mensaje = "Hay un error en su compra";
                    $respuesta = false;
                }
                $retorno = [];
                $retorno['respuesta'] = $respuesta;
                if (isset($mensaje)) {
                    $retorno['errorMsg'] = $mensaje;
                }
            }
        }
    }else{
        //si no hay compras para el usuario
        //LO QUE HARA SI no tenia compras anteriormente nuevo usuario
       
        $resp = $objCompraCon->crearCompra($idusuario,$idprod,$cicantidad);
        if($resp){
            $mensaje = "su compra se realizó correctamente";
            $respuesta = true;
        }else{
            $mensaje = "Hay un error en su compra";
            $respuesta = false;
        }
        $retorno = [];
        $retorno['respuesta'] = $respuesta;
        if (isset($mensaje)) {
            $retorno['errorMsg'] = $mensaje;
        }
    }

    
} else {
    $mensaje = "El stock de compra ingresado es superior al de depósito";
    $respuesta = false;
}
$retorno = [];
$retorno['respuesta'] = $respuesta;
if (isset($mensaje)) {
    $retorno['errorMsg'] = $mensaje;
}

echo json_encode($retorno);