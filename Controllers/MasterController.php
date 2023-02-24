<?php

class MasterController {
    

    public function getSlashesImg() {
        if( isset($_POST) && count($_POST) >= 0 ){
            $imagen = $_FILES['foto']['tmp_name'];
            $foto = addslashes( file_get_contents($imagen) );
        }
        return $foto;
    }
}