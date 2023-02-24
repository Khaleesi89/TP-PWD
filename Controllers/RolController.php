<?php

class RolController extends MasterController {
    use Errores;



    public function listarTodo(){
        $arrayBusqueda = [];
        $arrayTotal = Rol::listar($arrayBusqueda);
        $array = $arrayTotal['array'];
        return $array;        
    }



    public function buscarPorDesc($rodescripcion){
        $objRol = new Rol();
        $arrBuRol['rodescripcion'] = $rodescripcion;
        $objRol->buscar($arrBuRol);
        $idrol = $objRol->getIdrol();
        return $idrol;
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

    
}
