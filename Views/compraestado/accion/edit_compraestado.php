<?php

require_once('../../../config.php');
require '../../../Vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$objConCompraestado = new CompraestadoController();

$data = data_submitted();//[idcompraestado] => 147[idcompra] =>[cefechaini] => [idcompraestadotipo] => 2
//esto lo usaremos para personalizar el body del mail
$estadotipo = new Compraestadotipo();
$arrayB['idcompraestadotipo'] = $data['idcompraestadotipo'];
$tim = $estadotipo->buscar($arrayB);
$detalledeEstado = $estadotipo->getCetdescripcion();
//comprobamos que la cantidad de stock este disponible
$haystockDisponible = $objConCompraestado->cambiarStocksegunEstado($data);
if($haystockDisponible['respuesta']){
    //si la cantidad de stock esta disponible entonces hacemos el seteo de la fecha
    $rta = $objConCompraestado->modificarFechafin();
    if($rta){
        //y hacemos la nueva tupla con la info nueva
        $respuestita = $objConCompraestado->crearNuevoestadoElegido($data);
        if($respuestita['respuesta']){
            $mail = new PHPMailer(true);

            try{
                $mail->SMTPDebug = 0;                   
                $mail->isSMTP();                                            
                $mail->Host       =  'sandbox.smtp.mailtrap.io';                    
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = '8c2a69922e8a18';                     
                $mail->Password   = 'ab7c3e86b3d637';                               
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Destinatario y contenido del correo electrónico
                $mail->setFrom('sosaelva931@gmail.com', 'Marcia');
                $mail->addAddress('marcia.klimisch@est.fi.uncoma.edu.ar', 'Otra yo');
                $mail->isHTML(true);
                $mail->Subject = 'Estado de la compra';
                $mail->Body    = 'El estado actual de su compra es '.$detalledeEstado;

                $mail->send();
                $mensaje = 'El mensaje ha sido enviado correctamente';
                $rtaS = true;

            }catch(Exception $e) {
                $mensaje = "Ha ocurrido un error al enviar el mensaje: {$mail->ErrorInfo}";
                $rtaS = false;
            }
            
        }else{
            $mensaje = "No se ha podido realizar la operación";
        }
        $retorno['respuesta'] = $rtaS;
        if(isset($mensaje)){
            $retorno['errorMsg'] = $mensaje;
        }
    }else{
        $mess = "No se pudo modificar la fecha";
        $retorno ['respuesta'] = $mess;
    }
}else{
    $mensajito = "No hay stock disponible";
    $retorno['respuesta']= $mensajito;
}
echo json_encode($retorno);






