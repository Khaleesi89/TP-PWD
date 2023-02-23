<?php

class CompraestadotipoController extends MasterController{
    use Errores;

    public function busqueda(){
        $arrayBusqueda = [];
        $idCompraestadoTipo = Data::buscarKey('idcompraestadotipo');
        $cetdescripcion = Data::buscarKey('cetdescripcion');
        $cetdetalle = Data::buscarKey('cetdetalle');
        $arrayBusqueda = [
            'idcompraestadotipo' => $idCompraestadoTipo,
            'cetdescripcion' => $cetdescripcion,
            'cetdetalle' => $cetdetalle
        ];
        return $arrayBusqueda;
    }

    public function listarTodo(){
        $arrayBusqueda = [];
        $arrayTotal = Compraestadotipo::listar($arrayBusqueda);
        if($arrayTotal['respuesta'] == false){
            $array = [];
            
        }else{
            $array = $arrayTotal['array'];
        }
        
        return $array;        
    }


    public function buscarId() {
        $idBusqueda = Data::buscarKey( 'idcompraestadotipo' );
        if( $idBusqueda == false ){
            // Error
            $data['error'] = $this->warning( 'No se ha encontrado dicho registro' );
        } else {
            // Encontrado!
            $array['idcompraestadotipo'] = $idBusqueda;
            $objCompraestadotipo = new Compraestadotipo();
            $rta = $objCompraestadotipo->buscar( $array['idcompraestadotipo'] );
            if( $rta['respuesta'] == false ){
                $data['error'] = $this->manejarError( $rta );
            } else {
                $data['array'] = $objCompraestadotipo;
            }
            return $data;
        }
    }

    public function buscarIdDos(){
        $rta['respuesta'] = false;
        $idBusqueda = [];
        $idBusqueda['idcompraestadotipo'] = Data::buscarKey('idcompraestadotipo');
        $objCompraestadotipo = new Compraestadotipo();
        $objEncontrado = $objCompraestadotipo->buscar($idBusqueda);
        if($objEncontrado['respuesta']){
            $rta['respuesta'] = true;
            $rta['obj'] = $objCompraestadotipo;
        }
        return $rta;
    }

    public function insertar(){
        $data = $this->busqueda();
        $objCompraestadotipo = new Compraestadotipo();
        $objCompraestadotipo->setIdcompraestadotipo($data['idcompraestadotipo']);
        $objCompraestadotipo->setCetdescripcion($data['cetdescripcion']);
        $objCompraestadotipo->setCetdetalle($data['cetdetalle']);
        $rta = $objCompraestadotipo->insertar();
        return $rta;
    }

    public function modificar(){
        $rta = $this->buscarIdDos();
        $response = false;
        if($rta['respuesta']){
            //puedo modificar con los valores
            $valores = $this->busqueda();
            $objCompraestadotipo = $rta['obj'];
            $objCompraestadotipo->cargar($valores['cetdescripcion'], $valores['cetdetalle']);
            $rsta = $objCompraestadotipo->modificar();
            if($rsta['respuesta']){
                //todo gut
                $response = true;
            }
        }
        return $response;
    }

    public function eliminar(){
        $rta = $this->buscarIdDos();
        $response = false;
        if($rta['respuesta']){
            $objCompraestadotipo = $rta['obj'];
            $respEliminar = $objCompraestadotipo->eliminar();
            if($respEliminar['respuesta']){
                $response = true;
            }
        }else{
            //no encontro el obj
            $response = false;
        }
        return $response;
    }



}