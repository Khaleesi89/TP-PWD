<footer class="footer">
    <div class="box-container">
        <div class="box">
            <h3>Nuestras sucursales</h3>
            <a href="#"><i class="fas fa-map-marker-alt"></i>La FAI</a>
            <a href="#"><i class="fas fa-map-marker-alt"></i>La casa del Masi</a>
            <a href="#"><i class="fas fa-map-marker-alt"></i>La casa del Jero</a>
            <a href="#"><i class="fas fa-map-marker-alt"></i>La casa de Mar</a>
            <a href="#"><i class="fas fa-map-marker-alt"></i>La casa del Gonza</a>
        </div>
        <div class="box">
            <h3>Quick links</h3>
            <a href="#"><i class="fas fa-arrow-right"></i>Home</a>
            <a href="#"><i class="fas fa-arrow-right"></i>Ingresos</a>
            <a href="#"><i class="fas fa-arrow-right"></i>Reviews</a>
            <a href="#"><i class="fas fa-arrow-right"></i>Contacto</a>
        </div>
        <div class="box">
            <h3>Extra links</h3>
            <a href="#"><i class="fas fa-arrow-right"></i>Información de la cuenta</a>
            <a href="#"><i class="fas fa-arrow-right"></i>Productos comprados</a>
        </div>
    </div>
    <div class="share">
        <a href="#" class="fab fa-facebook-f"></a>
        <a href="#" class="fab fa-twitter"></a>
        <a href="#" class="fab fa-instagram"></a>
    </div>
    <div class="credit">
        <p>© Developed by Gonzalo Parra, Jerónimo Rojo, Marcia Klimisch & Maximiliano Ariel Hitter | 2022</p>
    </div>
</footer>

<script>
    window.onload = load();

    function load() {
        <?php if( isset($_GET['error']) ){
            $error = $_GET['error'];
            if( $error == 'log' ){
                echo "alert('Las credenciales son inválidas');";
            } elseif( $error == 'permiso' ){
                echo "alert('No posee permiso para acceder aquella página');";
            } else {
                echo "alert('Error desconocido');";
            }
        } ?>
    }
</script>

<!-- Swiper -->
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<!-- JS -->
<script src="../../Public/jsPuro/script.js"></script>
<!-- JQuery -->
<script src="../../Vendor/jquery.min.js"></script>
<script src="../../Vendor/jquery.easyui.min.js"></script>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>