<?php

class RolController extends MasterController {
    use Errores;

    public function busqueda(){
        $arrayBusqueda = [];
        $idrol = Data::buscarKey('idrol');
        $rodescripcion = Data::buscarKey('rodescripcion');
        $arrayBusqueda = [
            'idrol' => $idrol,
            'rodescripcion' => $rodescripcion
        ];
        return $arrayBusqueda;
    }

    public function listarTodo(){
        $arrayBusqueda = [];
        $arrayTotal = Rol::listar($arrayBusqueda);
        $array = $arrayTotal['array'];
        return $array;        
    }


    public function buscarId() {
        $idBusqueda = Data::buscarKey( 'idrol' );
        if( $idBusqueda == false ){
            // Error
            $data['error'] = $this->warning( 'No se ha encontrado dicho registro' );
        } else {
            // Encontrado!
            $array['idrol'] = $idBusqueda;
            $rol = new Rol();
            $rta = $rol->buscar( $array );
            if( $rta['respuesta'] == false ){
                $data['error'] = $this->manejarError( $rta );
            } else {
                $data['obj'] = $rol;
            }
            return $data;
        }
    }

    public function buscarPorDesc($rodescripcion){
        $objRol = new Rol();
        $arrBuRol['rodescripcion'] = $rodescripcion;
        $objRol->buscar($arrBuRol);
        $idrol = $objRol->getIdrol();
        return $idrol;
    }

    public function modificacionChetita() {
        $rta = $this->buscarId();
        $rol = $rta['array'];

        $roDescripcion = Data::buscarKey( 'rodescripcion' );
        $rol->setRodescripcion( $roDescripcion );

        $respuesta = $rol->modificar();
        return $respuesta;
    }

    public function insertar($data){
        $objRol = new Rol();
        $objRol->setRodescripcion($data['rodescripcion']);
        $rta = $objRol->insertar();
        return $rta;
    }

    public function modificar($data){
        $objRol = new Rol();
        $array['idrol'] = $data['idrol'];
        $esta = $objRol->buscar($array);
        if($esta['respuesta']){
            $objRol->setRodescripcion($data['rodescripcion']);
            $rsta = $objRol->modificar();
            if($rsta['respuesta']){
                //todo gut
                $response = true;
            }else{
                $response = false;
            }
        }
        return $response;
    }

    //buscar rol
    public function buscar($data){
        $rol = new Rol();
        $rta = $rol->buscar($data);
        if($rta ['respuesta']){
            $salida = $rol;
        }else{
            $salida = false;
        }
        return $salida;
    }

    public function eliminar() {
        $rta = $this->buscarId();
        $response = false;
        if($rta['obj']){
            $objRol = $rta['obj'];
            $respEliminar = $objRol->eliminar();
            if($respEliminar['respuesta']){
                $response = true;
            }
        }
        return $response;
    }

}
