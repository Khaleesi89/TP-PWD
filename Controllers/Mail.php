<?php

trait Mail
{
    public static function enviarMail($email, $contenido)
    {
        $mail = new PHPMailer();
        //Set mailer to use smtp
        $mail->isSMTP();
        //define smtp host
        $mail->Host = 'sandbox.smtp.mailtrap.io'; 
        //enable smtp auth 
        $mail->SMTPAuth = "true";
        //set type encrypt 
        $mail->SMTPSecure = "tls";
        //set port 
        $mail->Port = "587";
        //set gmial user 
        $mail->Username = '8c2a69922e8a18';
        //set pass 
        $mail->Password = 'ab7c3e86b3d637'; 
        //set email subj 
        $mail->Subject = "Cambio de estado de la compra";
        //set sender 
        $mail->setFrom("sosaelva931@gmail.com");
        //email body 
        $mail->Body = $contenido;
        //add recipient
        if($email != '' && $email != null){
            $mail->addAddress($email);
        } else{
            $mail->addAddress("sosaelva931@gmail.com", "Marcia");
        }
        //send 
        if ($mail->Send()) {
            $respuesta = true;
        } else {
            $respuesta = false;
        }
        //closing smtp
        $mail->smtpClose();
        return $respuesta;
    }
}
