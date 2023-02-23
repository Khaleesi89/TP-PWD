<?php require_once 'vendor/autoload.php';

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use BotMan\BotMan\Cache\SymfonyCache;

require_once('Conversaciones/CharlaAmistosa.php');
require_once('Conversaciones/ComoComprar.php');
require_once('Conversaciones/ComoRegistrarse.php');

$config = [];

DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

$adapter = new FilesystemAdapter();

$botman = BotManFactory::create($config, new SymfonyCache($adapter));

$botman->hears('(.*)hola(.*)|(.*)hello(.*)|(.*)buenas(.*)|(.*)buenos(.*)|(.*)que onda(.*)', function($bot){
    $bot->typesAndWaits(2);
    $bot->startConversation(new CharlaAmistosa());
})->skipsConversation();

$botman->fallback(function ($bot) {
    //del driver que utilizamos obtenemos el obj mensaje del usuario
    $mensaje = $bot->getMessage();
    //Para que su bot se sienta y actúe de forma más humana, puede hacer que envíe indicadores de "escribiendo...".
    $bot->typesAndWaits(2);
    //damos una respuesta inmediata con reply al usuario
    $bot->reply('No comprendo \'' . $mensaje->getText() . '\', sea más específic@, por favor :)');
});

$botman->hears('(.*)compra(.*)|(.*)libro(.*)|(.*)adquirir(.*)|(.*)regalo(.*)|(.*)precio(.*)|(.*)contac(.*)|(.*)ubic(.*)', function ($bot) {
    $bot->typesAndWaits(2);
    $bot->startConversation(new ComoComprar());
})->skipsConversation();

$botman->hears('(.*)usuari(.*)|(.*)email(.*)|(.*)cómo compr(.*)|(.*)como compr(.*)|(.*)registrar(.*)|(.*)logue(.*)', function ($bot) {
    $bot->typesAndWaits(2);
    $bot->startConversation(new ComoRegistrarse());
})->skipsConversation();

$botman->hears('(.*)chau(.*)|me voy(.*)|adios(.*)|nos vemos(.*)|gracias|nada', function ($bot) {
    $bot->typesAndWaits(1);
    $bot->reply('Un placer, hasta pronto!');
})->stopsConversation();

$botman->listen();