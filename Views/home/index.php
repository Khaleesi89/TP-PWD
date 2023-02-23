<?php
    require_once('../../config.php');
    require_once('../templates/preheader.php');
    $objProducto = new ProductoController();
    $arr = [];
    $listaProductos = $objProducto->listarTodo( $arr );
    
?>
    
    
    <!-- Home -->
    <section class="home" id="home">
        <div class="row">
            <div class="content">
                <h3>Al 75% de descuento</h3>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aut ad enim debitis delectus a voluptates optio qui commodi, ratione totam.</p>
                <a href="#" class="btn">Comprar ya</a>
            </div>

            <div class="swiper books-slider">
                <div class="swiper-wrapper">
                    <?php foreach( $listaProductos as $producto ){
                        $data = $producto->dameDatos();
                        $foto = $data['foto'];
                        echo "<a href=\"../producto/producto_list.php\" class=\"swiper-slide\">$foto</a>";
                    } ?>
                </div>
                <img src="../../Public/img/stand.png" class="stand" alt="">
            </div>
        </div>
    </section>

    <!-- Iconcitos fachas -->
    <section class="icons-container">
        <div class="icons">
            <i class="fas fa-plane"></i>
            <div class="content">
                <h3>Envíos gratis a todo el país</h3>
                <p>En pedidos mayores a $4000</p>
            </div>
        </div>
        <div class="icons">
            <i class="fas fa-lock"></i>
            <div class="content">
                <h3>Pagos seguros</h3>
                <p>Tarjetas de crédito, débito y mercado pago</p>
            </div>
        </div>
        <div class="icons">
            <i class="fas fa-redo-alt"></i>
            <div class="content">
                <h3>Reembolsos</h3>
                <p>Luego de haber recibido el producto</p>
            </div>
        </div>
        <div class="icons">
            <i class="fas fa-headset"></i>
            <div class="content">
                <h3>Atención 24/7</h3>
                <p>Consúltenos en cualquier momento</p>
            </div>
        </div>
    </section>

    <section class="ingresos" id="ingresos">
        <h1 class="heading"> <span>Nuevos Ingresos</span> </h1>
        <div class="swiper ingresos-slider">
            <div class="swiper-wrapper">
                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="../../Public/img/libro-1.png" alt="">
                    </div>
                    <div class="content">
                        <h3>El Señor de los Anillos - Las Dos Torres</h3>
                        <div class="price">$3599</div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </a>
                
                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="../../Public/img/libro-2.png" alt="">
                    </div>
                    <div class="content">
                        <h3>Nuevo ingreso</h3>
                        <div class="price">$2599</div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </a>

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="../../Public/img/libro-3.png" alt="">
                    </div>
                    <div class="content">
                        <h3>Nuevo ingreso</h3>
                        <div class="price">$2599</div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </a>

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="../../Public/img/libro-4.png" alt="">
                    </div>
                    <div class="content">
                        <h3>Nuevo ingreso</h3>
                        <div class="price">$2599</div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </a>

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="../../Public/img/libro-5.png" alt="">
                    </div>
                    <div class="content">
                        <h3>Nuevo ingreso</h3>
                        <div class="price">$2599</div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </a>

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="../../Public/img/libro-6.png" alt="">
                    </div>
                    <div class="content">
                        <h3>Nuevo ingreso</h3>
                        <div class="price">$2599</div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <section class="oferta">
        <div class="content">
            <h3>Oferta del dia!</h3>
            <h1>50% de descuento</h1>
            <p>Conoce el caso de Sherlock Holmes y las travesías por la Universidad de la UNCO. Explora por el mundo de la programación autodidáctica y fatídica, los sinsabores de la falta de papel en los momentos menos indicados...</p>
            <a href="#" class="btn">Compralo ya!</a>
        </div>
        <div class="image">
            <img src="../../Public/img/oferta.jpg" alt="compralo wachin">
        </div>
    </section>

    <section class="reviews" id="reviews">
        <h1 class="heading"><span>Reviews de clientes</span></h1>
        <div class="swiper reviews-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <img src="../../Public/img/pic-1.png" alt="">
                    <h3>John Salchichon</h3>
                    <p>La verda que aprendí a leer con el libro de Manuelita y desde ahí, no quiero volver a leer!</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <img src="../../Public/img/pic-2.png" alt="">
                    <h3>John Snow</h3>
                    <p>Estuve aprendiendo sobre MVC y la verdad que no me funca mucho, pero me encanta la buena onda que le ponen todos para aprender...Hay equipo!!</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <img src="../../Public/img/pic-3.png" alt="">
                    <h3>Susana Horia</h3>
                    <p>Un saludito para todos los que me están mirando por TV. Soy la niña reconrosa... MIRA DE QUIEN TE BURLASTE, BARNEY!!!</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <img src="../../Public/img/pic-4.png" alt="">
                    <h3>Esteban Quito</h3>
                    <p>Yo me casé con Flor De Vivero porque me inspiré en los libros de Jane Austen y hoy la odio como los libros de Kafka...</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <img src="../../Public/img/pic-6.png" alt="">
                    <h3>Armando Paredes</h3>
                    <p>Era tal su  descontento que trabajé con un albañil por 10 años y lo único que me salía bien era preparar el mate!</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <img src="../../Public/img/pic-5.png" alt="">
                    <h3>Armando Bronca Segura</h3>
                    <p>El otro día terminé peleando con el profesor, y siempre me pregunto, para que me invitan?? Si saben cómo me pongo!!</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="newsletter">
        <form action="" method="">
            <h3>Suscríbete para obtener las últimas novedades</h3>
            <input type="email" name="mail" id="mail" placeholder="Ingrese su email" class="box">
            <input type="submit" value="Suscribirse" class="btn">
        </form>
    </section>

    <?php
    require_once('../templates/footer.php');
    ?>