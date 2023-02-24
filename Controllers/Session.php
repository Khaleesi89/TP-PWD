<?php

class Session extends MasterController {


    /**
     * Getters & Setters
     * Obtiene y setea los índices de la variable $_SESSION
     */
    public function getIdusuario() {
        return $_SESSION['idusuario'];
    }
    public function setIdusuario( $idusuario ){
        $_SESSION['idusuario'] = $idusuario;
    }

    public function getUsnombre() {
        return $_SESSION['usnombre'];
    }
    public function setUsnombre( $usnombre ){
        $_SESSION['usnombre'] = $usnombre;
    }

    public function getUspass() {
        return $_SESSION['uspass'];
    }
    public function setUspass( $uspass ){
        $_SESSION['uspass'] = $uspass;
    }

    public function getUsRol() {
        if (isset($_SESSION['usRol'])) {
            $rol = $_SESSION['usRol'];
        } else {
            $rol = '';
        }

        return $rol;
    }
    public function setUsRol( $usRol ){
        $_SESSION['usRol'] = $usRol;
    }


    /**
     * Método que verifica si la sesión esta activa o no
     * Retorna true si está activa, caso contrario false
     * @param void
     * @return boolean
     */
    public function activa() {
        $bandera = false;
        if (isset($_SESSION['usnombre'])) {
            $bandera = true;
        }
        return $bandera;
    }

    /**
     * Método que finaliza una sesión
     * @param void
     * @return void
     */
    public function cerrar() {
        session_unset();
        session_destroy();
    }

    // Get rol de usuario
    public function getRol() {
        $objUsuarioRol = new UsuarioRolController();
        $rolUsuario = [];
        $listaUsuarios = $objUsuarioRol->getUsuarios();
        foreach ($listaUsuarios as $usuario) {
            $id = $usuario->getIdusuario();
            if ($id == $this->getIdusuario()) {
                $rolUsuario = $objUsuarioRol->buscarRoles();
            }
        }
        return $rolUsuario;
    }

    public function isAdmin( $rol ){
        $bandera = false;
        if ($rol == 'Admin' && $rol == $this->getUsRol()) {
            $bandera = true;
        }
        return $bandera;
    }

    // dame datos de idusuario, roles del usuario
    public function dameDatos() {
        $data = [];
        $data['idusuario'] = $this->getIdusuario();
        $data['rolesusuario'] = $this->getRol();
        return $data;
    }

    public function rolesUsuario() {
        $objUsuarioRolCon = new UsuarioRolController();
        $rta = $objUsuarioRolCon->buscarRoles();
        $arrayRoles = $objUsuarioRolCon->getRoles();
        $rolesSimple = [];
        foreach ($arrayRoles as $key => $value) {
            $data = $value->dameDatos();
            $rolesSimple[$data['idrol']] = false;
        }
        $rolesTexto = [];
        if (count($rta) != 0) {
            foreach ($rta as $key => $value) {
                $data = $value->dameDatos();
                $rolesTexto[$data['idrol']] = true;
            }
        }
        $string = "";
        $arrayOtro = [];
        if (count($rolesTexto) != 0) {
            foreach ($rolesSimple as $id => $idrolArray) {
                $valor = 'false';
                if (array_key_exists($id, $rolesTexto)) {
                    $rolesSimple[$id] = true;
                    $valor = 'true';
                }
                $arrayOtro["rol$id"] = $valor;
                if ($string == '') {
                    $string .= "[$id => $valor,";
                } else {
                    $string .= " $id => $valor,";
                }
            }
        }
        $string = substr($string, 0, -1);
        $string .= "] ";
        $objNuevo = (object)array('data' => $arrayOtro);
        return $objNuevo;
    }

}