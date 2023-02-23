<?php

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class ComoRegistrarse extends Conversation
{

    /**
     * creamos un obj consulta, obj consulta contiene objetos botones,
     * preguntamos si la respuesta fue de forma interactiva (en este caso si respondio por botones)
     * y sino le insistimos que responda con botones, si es interactiva leemos el valor de la respuesta 
     * y le damos intrucciones, para href si no usamos target='_blank' se carga la pagina dentro del chat, probar jaja
     */
   
    public function mostrarOpc()
    {
        //creamos una pregunta que contendra botones
        //tener en cuenta que los value tienen que ser diferentes al valor que contienen dentro, sino no lo toma
        $preguntas = Question::create('Opciones de consulta')
        ->addButtons([ //arreglo de los botones de opciones
                Button::create('Cómo registrarse')->value('1'),
                Button::create('Problemas con su email?')->value('2'),
                Button::create('Cómo realizar una compra?')->value('3'),
                Button::create('Problemas para loguearse?')->value('4'),

            ]);
        $this->ask($preguntas, function ($answer) {
            //pregunta a traves de los botones y el usuario responde
            //isInteractiveMessageReply para detectar si el usuario interactuó con el mensaje e hizo clic en un botón o simplemente ingresó texto.
            if ($answer->isInteractiveMessageReply()) {
                //es valido xq el usuario respondio con los botones
                if ($answer->getValue() == '1') {
                    //
                    $this->say('Para realizar su registro, deberá tener un mail y su DNI. Le pediremos unos datos de tu tarjeta de DNI para comprobar su identidad. En su mail, recibirá un link para activar su cuenta. Si no realiza esa validación, su cuenta permanecerá inactiva y no podrá realizar comprar.');
                } elseif ($answer->getValue() == '2') {
                    $this->say('Si tiene problemas con su mail y no puede validar su cuenta, deberá realizar una nueva registración con un mail válido. La registración anterior caducará después de 30 días de creada pero no le impedirá realizar comprar.');
                } elseif ($answer->getValue() == '3') {
                    $this->say('Ud. verá el menú con el stock disponible y podrá realizar la compra cliqueando en "COMPRAR" y se guardará en el carrito de comprar y una vez que tenga su carrito de compra completo con sus compras, presione "FINALIZAR COMPRA" y lo dirigirá al sector de pagos para realizar el pago según el medio que utilice.');
                } elseif ($answer->getValue() == '4') {
                    $this->say('Ponganse en contacto con nosotros, mande un email a yonnylibros@gmail.com y lo ayudaremos.');
                } 
            } else {
                //el usuario no respondio a traves de los botones dados, si queremos leer ambas, solo sacamos la condicion
                $this->say('Seleccione una opción para seguir o si quiere salir ponga "chau"');
                $this->repeat();//se repite esta funcion
            }
        });
    }
   
   
   
   
   
     public function run()
    {
        $this->mostrarOpc();
    }
}
