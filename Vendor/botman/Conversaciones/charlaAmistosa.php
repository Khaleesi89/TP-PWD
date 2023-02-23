<?php

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\BotMan;

date_default_timezone_set('America/Argentina/Buenos_Aires');


class CharlaAmistosa extends Conversation
{

    private $date;
    protected $firstName;

    public function __construct()
    {
        $this->date = date('H');
    }

    public function saludar()
    {
        //segun la hora del dia es si saluda buenos dias, o buenas tardes o buenas noches
        $saludo = '';
        if ($this->date >= 5 && $this->date <= 12) {
            $saludo = 'Buenos dias!';
        } else if ($this->date >= 13 && $this->date <= 19) {
            $saludo = 'Buenas tardes!';
        } else {
            $saludo = 'Buenas noches!';
        }

        $this->say($saludo);

        $this->ask('Cuál es tu nombre?', function (Answer $answer) {
            //save result
            $this->firstName = $answer->getText();
            $this->say('Un gusto conocerte ' . $this->firstName .", en que puedo ayudarte?");
            $this->preguntar();
        });
    }

    public function preguntar(){
        
    }

    public function run()
    {
        $this->saludar();
    }

}
