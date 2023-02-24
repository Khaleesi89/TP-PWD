<?php
class ProductoController extends MasterController{
    use Errores;


    //buscar producto 
    public function buscar($data){
        $producto = new Producto();
        $rta = $producto->buscar($data);
        if($rta['respuesta']){
            $rta = $producto;
        }else{
            $rta = false;
        }
        return $rta;
        
    
    }


    public function listarTodo($array){
        if(empty($array)){
            $arrayBusqueda = [];
            $arrayTotal = Producto::listar($arrayBusqueda);
            if(array_key_exists('array', $arrayTotal)){
                $array = $arrayTotal['array'];
            }else{
                $array = [];
            }
        }else{
            $arrayTotal = Producto::listar($array);
            if(array_key_exists('array', $arrayTotal)){
                $array = $arrayTotal['array'];
            }else{
                $array = [];
            }

        }
        
        return $array;        
    }


    public function insertar($data){
        $objCompra = new Producto();
        $objCompra->setProNombre( $data['pronombre'] );
        $objCompra->setSinopsis( $data['sinopsis'] );
        $objCompra->setProCantStock( $data['procantstock'] );
        $objCompra->setAutor( $data['autor'] );
        $objCompra->setPrecio( $data['precio'] );
        $objCompra->setIsbn( $data['isbn'] );
        $objCompra->setCategoria( $data['categoria'] );
        $foto = $this->getSlashesImg();
        $objCompra->setFoto($foto);
        $rta = $objCompra->insertar();
        return $rta;
    } 


    public function modificar($data){
        $objProducto = new Producto();
        $array['idproducto'] = $data['idproducto'];
        $rta = $objProducto->buscar($array);
        if($rta['respuesta']){
            $objProducto->setProNombre($data['pronombre']);
            $objProducto->setSinopsis($data['sinopsis']);
            $objProducto->setProCantStock($data['procantstock']);
            $objProducto->setAutor($data['autor']);
            $objProducto->setPrecio($data['precio']);
            $objProducto->setIsbn($data['isbn']);
            $objProducto->setCategoria($data['categoria']);
            $rsta = $objProducto->modificar();
            if($rsta['respuesta']){
                //todo gut
                $response = true;
            }else{
                $response = false;
            }
        }else{
            //no encontro el obj
            $response = false;
        }
        return $response;
    }

    

    public function obtenerStockPorId($idproducto){
        $arrBus = [];
        $arrBus['idproducto'] = $idproducto;
        $objProducto = new Producto();
        $rta = $objProducto->buscar($arrBus);
        if($rta['respuesta']){
            $respuesta = $objProducto->getProCantStock();
        }else{
            $respuesta = false;
        }
        return $respuesta;
    }
}