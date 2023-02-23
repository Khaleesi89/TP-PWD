<?php
    require_once('../../config.php');  
    $obtenerURL = explode('/', $_SERVER['REQUEST_URI']);
    $obtenerURL = array_reverse($obtenerURL);
    $var = 'producto';
    $urlActual = $obtenerURL[1];
    
    $bandera = true;
    if($urlActual == $var){
        $bandera = false;
    }
    if($urlActual == 'home'){
        $bandera = false;
    }
    if($bandera){
        header($PRINCIPAL."?error=permiso");
    }
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yonny ameo</title>
    <link rel="icon" type="image/x-icon" href="../../Public/img/favicon.png">
    <script src="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js"></script>
    <script>
        var botmanWidget = {
            introMessage: 'Bienvenido',
            frameEndpoint: '../../Vendor/botman/chat.php',
            chatServer: '../../Vendor/botman/botman.php',
            introMessage: 'Bienvenid@ soy <b>Yonny</b><br>En que puedo servirle?', //saludo inicial
            title: 'Asistente Yonny', //titulo del chat
            dateTimeFormat: 'Y-m-d H:i:s', //formato con el cual trabajaremos
            placeholderText: 'Enviar mensaje...',
            mainColor: '#27ae60', //encabezado
            bubbleBackground: '#219150', //burbuja//el sobre es el icono predeterminado
            // bubbleAvatarUrl: '../Archivos/icono.png',
            aboutText: 'Producido por el grupo Copado',
        }
    </script>
    <!-- CSS -->
    <link rel="stylesheet" href="../../Public/cssPuro/newStyle.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <!-- Bootstrap -->
    <!-- <link rel="stylesheet" href="../../Public/bootstrap-5.2.2-dist/css/bootstrap.min.css"> -->

    <link rel="stylesheet" href="../../Vendor/themes/default/easyui.css">
    <link rel="stylesheet" href="../../Vendor/themes/icon.css">
    <link rel="stylesheet" href="../../Vendor/themes/color.css">
    
</head>

<body>

    <!-- Header -->
    <header class="header">
        <div class="header-1">
            <a href="../home/index.php" class="logo"><i class="fas fa-book"></i> Yonny</a>
                <div class="icons">
                    <div id="search-btn" class="fas fa-search"></div>
                    <a href="#" class="fas fa-shopping-cart"></a>
                    
                    <div id="login-btn" class="fas fa-user"></div>
                </div>
        </div>

        
        
        <div class="header-2">
            <nav class="navbar">
            
                <a href="../home/index.php">Home</a>
                <a href="<?php echo $PROD ?>">Productos</a>

            </nav>
        </div>
    </header>
    
    <!-- Nav pal responsive -->
    <nav class="bottom-navbar">
        <a href="#" class="fas fa-home"></a>
        <a href="#ingresos" class="fas fa-tags"></a>
        <a href="#reviews" class="fas fa-comments"></a>
        <a href="#contacto" class="fas fa-blogs"></a>
    </nav>


    <!-- Formulario de login -->
    <div class="login-form-container">
        <div id="close-login-btn" class="fas fa-times"></div>
        <form action="../accion/accionLogin.php" method="POST">
            <h3>Login</h3>
            <span>Usuario</span>
            <input type="text" name="usnombre" class="box" placeholder="Ingrese su usuario" id="">
            <span>Contraseña</span>
            <input type="password" name="uspass" class="box" placeholder="Ingrese su contraseña" id="">
            <input type="submit" value="Sign in" class="btn">
            <p>Te olvidaste la contraseña pa? <a href="#">Clickea aca ;)</a> </p>
            <p>No tenes una cuenta? <a href="../logs/signup.php">Create una papu</a> </p>
        </form>
    </div>