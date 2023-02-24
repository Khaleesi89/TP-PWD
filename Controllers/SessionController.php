<?php

class SessionController extends MasterController {

    public function __construct(){
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function getUsnombre() {
        if(array_key_exists('usnombre', $_SESSION)){
            $usnombre = $_SESSION['usnombre'];
        }else{
            $usnombre = false;
        }
        return $usnombre;
    }

    public function getIdusuario() {
        return $_SESSION['idusuario'];
    }


    /** Identifica si la sesion esta activa
     * @return bool
     */
    public function activa() {
        $bandera = false;
        if( isset($_SESSION['idusuario']) && $_SESSION['usnombre'] != false ){
            $bandera = true;
        }else{
            $bandera = false;
        }
        
        return $bandera;
    }

    /** Identificamos si las credenciales ingresadas concuerdan con algun usuario
     * en la base de datos 
    * @return bool
    */
    public function validarCredenciales($data) {
        $usnombre = $data['usnombre'];
        $uspass = $data['uspass'];
        if( ($usnombre != false && $uspass != false) || isset($_SESSION['usnombre']) ){
            
            //a buscar
            
            $objUsuario = new Usuario();
            $arrBusUs = array('usnombre' => $usnombre, 'uspass' => $uspass);
            
            $rta = $objUsuario->buscar($arrBusUs);
            
            if ($rta['respuesta']) {
                try {
                    $idusuario = $objUsuario->getIdusuario();
                    
                } catch (\Throwable $th) {
                    $idusuario = 0;
                }
                if ($idusuario != NULL && $idusuario != 0) {
                    //esta bien
                    $_SESSION['idusuario'] = $idusuario;
                    $_SESSION['usnombre'] = $usnombre;
                    
                    $retorno = true;
                } else {
                    $retorno = false;
                }
            }
        } else {
            
            $retorno = false;
        }
        return $retorno;
    }

    /**
     * Con el ID del usuario obtenemos su rol
     * @return array
     */
    public function obtenerRol() {
        $arrBusU['idusuario'] = $this->getIdusuario();//obtengo id usuario
        $rta = Usuariorol::listar($arrBusU);
        if( array_key_exists('array', $rta) ){
            $roles = $rta['array'];
        } else {
            $roles = [];
        }
        return $roles;
    }

    public function listaRoles() {
        $roles = $this->obtenerRol();
        $rolesString = [];
        $rolesId = [];
        foreach ($roles as $key => $value) {
        }
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
    
    public function setRolPrimo($rol, $id){
        $_SESSION['rolPrimo'] = $rol;
        $_SESSION['rolPrimoId'] = $id;    
    }

    public function getRolPrimo(){
        return $_SESSION['rolPrimo'];    
    }

    public function getRolPrimoId(){
        return $_SESSION['rolPrimoId'];    
    }

    public function obtenerMenues(){
        $objMenuCon = new MenuController();
        $menus = $objMenuCon->obtenerMenuesPorRol($this->getRolPrimoId());
        return $menus;    
    }

    public function obtenerTodosMenues(){
        $arrBuMe['medeshabilitado'] = NULL;
        $menuesTotales = Menu::listar($arrBuMe);
        $arrMen = [];
        foreach ($menuesTotales['array'] as $key => $value) {
            $arrMenu = $value->dameDatos();
            array_push($arrMen, $arrMenu);
        }
        return $arrMen;    
    }

    public function tienePermiso(){
        $obtenerURL = explode('/', $_SERVER['REQUEST_URI']);
        $obtenerURL = array_reverse($obtenerURL);
        $var = 'ABM';
        $urlActual = $var.$obtenerURL[1];
        $menuesDelUsuario = $this->obtenerMenues();//obtiene los nombres del menu
        $bandera = true;
        foreach ($menuesDelUsuario as $key => $value) {
            if($value == $urlActual || $urlActual == 'ABMhome'){
                $bandera = false;
            }
        }
        return $bandera;
    }

}