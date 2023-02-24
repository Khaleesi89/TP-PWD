<?php

class MenuRolController extends MasterController {
    use Errores;


    public function listarTodo() {
        $arrayBusqueda = [];
        $arrayTotal = Menurol::listar( $arrayBusqueda );
        $array = $arrayTotal['array'];
        return $array;
    }

    public function insertar( $data ){
        $newMenuRol = new Menurol();
        $newMenuRol->setIdmr( $data['idmr'] );
        $newMenuRol->setObjMenu( $data['objMenu'] );
        $newMenuRol->setObjRol( $data['objRol'] );

        $operacion = $newMenuRol->insertar();
        if( $operacion['respuesta'] == false ){
            $rta = $operacion['errorInfo'];
        } else {
            $rta = $operacion['respuesta'];
        }
        return $rta;
    }



    public function baja( $param ){
        $bandera = false;
        if( $param->getIdmr() !== null ){
            if( $param->eliminar() ){
                $bandera = true;
            }
        }
        return $bandera;
    }

    
    

}
