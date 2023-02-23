<?php

require_once('../templates/preheader.php');


$objConPro = new ProductoController();

if( $objSession->getUsnombre() != null ){
    try {
        $rol = $objSession->getRolPrimo();
        if ($rol != '') {
            if ($rol == 'Admin' || $rol == 'Deposito') {
                $arrBuPro = [];
                $lista = $objConPro->listarTodo($arrBuPro);
            } elseif ($rol == 'Cliente') {
                $arrBuPro['prdeshabilitado'] = NULL;
                $lista = $objConPro->listarTodo($arrBuPro);
            }
        }
    } catch (\Throwable $th) {
        $rol = '';
        $lista = []; //  ['idproducto' => '', 'pronombre' => '', 'sinopsis'=>'', 'procantstock'=>'', 'autor'=>'', 'precio'=>'', 'isbn'=>'', 'categoria'=>''];
    }
}

?>

<div class="container-fluid p-5 my-1 d-flex justify-content-center producto">
    <div class="row">
        <div class="col-sm-12">

            <table id="dg" title="Productos" class="easyui-datagrid" style="width:1200px;height:700px" url="accion/listar_producto.php" toolbar="#toolbar" pagination="true" fitColumns="true" singleSelect="true">
                <thead>
                    <tr>
                        <th field="idproducto" width="2%">Id</th>
                        <th field="pronombre" width="10%">Nombre producto</th>
                        <th field="sinopsis" width="20%">Sinopsis</th>
                        <th field="procantstock" width="5%">Stock</th>
                        <th field="autor" width="10%">Autor</th>
                        <th field="precio" width="5%">Precio</th>
                        <th field="isbn" width="5%">ISBN</th>
                        <th field="categoria" width="10%">Categoría</th>
                        <th field="prdeshabilitado" width="10%">Deshabilitado</th>
                        <th field="foto" width="20%">Portada</th>
                    </tr>
                </thead>
            </table>

            <div id="toolbar" style="padding:4px">
                <?php
                if( isset($rta)  ){
                    if( $rol == 'Admin' || $rol == 'Deposito' ){ // Admin o Depósito
                        echo "<a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-add\" plain=\"true\" onclick=\"newProducto()\">Nuevo producto</a>
                        <a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-edit\" plain=\"true\" onclick=\"editProducto()\">Editar producto</a>
                        <a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-remove\" plain=\"true\" onclick=\"destroyProducto()\">Eliminar producto</a>";
                    } elseif( $rol == 'Cliente' || $rta == false ){ // Cliente
                        echo "<a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-remove\" plain=\"true\" onclick=\"comprar()\">Comprar</a>";
                    }
                } else {
                    
                }
                ?>
            </div>

            <div id="dlg" class="easyui-dialog" style="width:600px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
                <form id="fm" method="POST" enctype="multipart/form-data" novalidate style="margin:0; padding:20px 50px;">
                    <h3>Producto información</h3>
                    <div style="margin-bottom:10px;">
                        <input name="pronombre" id="pronombre" class="easyui-textbox" required="true" label="Nombre" style="width:100%;">
                    </div>
                    <div style="margin-bottom:10px;">
                        <input name="sinopsis" id="sinopsis" class="easyui-textbox" required="true" label="Sinopsis" style="width:100%;">
                    </div>
                    <div style="margin-bottom:10px;">
                        <input name="procantstock" id="procantstock" class="easyui-textbox" required="true" label="Stock" style="width:100%;">
                    </div>
                    <div style="margin-bottom:10px;">
                        <input name="autor" id="autor" class="easyui-textbox" required="true" label="Autor" style="width:100%;">
                    </div>
                    <div style="margin-bottom:10px;">
                        <input name="precio" id="precio" class="easyui-textbox" required="true" label="Precio" style="width:100%;">
                    </div>
                    <div style="margin-bottom:10px;">
                        <input name="isbn" id="isbn" class="easyui-textbox" required="true" label="ISBN" style="width:100%;">
                    </div>
                    <div style="margin-bottom:10px;">
                        <input name="categoria" id="categoria" class="easyui-textbox" required="true" label="Categoria" style="width:100%;">
                    </div>
                    <div style="margin-bottom:10px;">
                        <input class="easyui-filebox" class="foto" name="foto" id="foto" style="width:100%" data-options="
                            prompt:'Selecciona un archivo',
                            onChange: function(value){
                                var f = $(this).next().find('input[type=file]')[0];
                                if (f.files && f.files[0]){
                                    var reader = new FileReader();
                                    reader.onload = function(e){
                                        $('#foto').attr('src', e.target.result);
                                    }
                                    reader.readAsDataURL(f.files[0]);
                                }
                            }">
                    </div>
                </form>

                <div id="dlg-buttons">
                    <a href="javascript:void(0)" class="easyui-button c6" iconCls="icon-ok" onclick="guardarProducto()" style="width:90px">Aceptar</a>
                    <a href="javascript:void(0)" class="easyui-button" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Formulario de compra -->
<div id="dlg1" class="easyui-dialog" style="width:600px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg1-buttons'">
    <form id="fm1" method="POST" novalidate style="margin:0; padding:20px 50px;">
        <h3>Producto información</h3>
        <div style="margin-bottom:10px;">
            <input name="pronombre" id="pronombre" class="easyui-textbox" required="true" label="Nombre" style="width:100%;" readonly>
        </div>
        <div style="margin-bottom:10px;">
            <input name="sinopsis" id="sinopsis" class="easyui-textbox" required="true" label="Sinopsis" style="width:100%;" readonly>
        </div>
        <div style="margin-bottom:10px;">
            <input name="procantstock" id="procantstock" class="easyui-textbox" required="true" label="Stock" style="width:100%;" readonly>
        </div>
        <div style="margin-bottom:10px;">
            <input name="autor" id="autor" class="easyui-textbox" required="true" label="Autor" style="width:100%;" readonly>
        </div>
        <div style="margin-bottom:10px;">
            <input name="precio" id="precio" class="easyui-textbox" required="true" label="Precio" style="width:100%;" readonly>
        </div>
        <div style="margin-bottom:10px;">
            <input name="isbn" id="isbn" class="easyui-textbox" required="true" label="ISBN" style="width:100%;" readonly>
        </div>
        <div style="margin-bottom:10px;">
            <input name="categoria" id="categoria" class="easyui-textbox" required="true" label="Categoria" style="width:100%;" readonly>
        </div>
        <!-- Input de la cantidad a comprar -->

        <!-- <div style="margin-bottom:20px">
                    <input class="easyui-numberbox" min="0" max="5" name="cicantidad" required="true" id="cicantidad" style="width:100%;">
                </div> -->
        <div style="margin-bottom:10px; margin-top:15px;">
            <label for="cicantidad" style="width:20%; display:inline;">Cantidad a comprar:</label>
            <input type="number" name="cicantidad" id="cicantidad" class="easyui-number" label="Cantidad de compra:" required="true" style="width:60%;display:inline;" min="1">
        </div>

    </form>
    <div id="dlg1-buttons">
        <a href="javascript:void(0)" class="easyui-button c6" iconCls="icon-ok" onclick="guardarCompra()" style="width:90px">Aceptar</a>
        <a href="javascript:void(0)" class="easyui-button" iconCls="icon-cancel" onclick="javascript:$('#dlg1').dialog('close')" style="width:90px">Cancelar</a>
    </div>
</div>

<script>
    var url;
    var number = document.getElementById('cicantidad');
    var cantStock;

    function newProducto() {
        $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nuevo producto');
        $('#fm').form('clear');
        url = 'accion/insertar_producto.php';
    }

    function editProducto() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Editar producto');
            $('#fm').form('load', row);
            url = 'accion/edit_producto.php?idproducto=' + row.idproducto;
        }
    }

    function guardarProducto() {
        $('#fm').form('submit', {
            url: url,
            onSubmit: function() {
                return $(this).form('validate');
            },
            success: function(result) {
                var result = eval('(' + result + ')');
                //alert('Volvio servidor');
                if (!result.respuesta) {
                    $.messager.show({
                        title: 'Error',
                        msg: result.errorMsg
                    });
                } else {
                    $('#dlg').dialog('close');
                    $('#dg').datagrid('reload');
                }
            }
        })
    }

    function comprar() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $('#dlg1').dialog('open').dialog('center').dialog('setTitle', 'Comprar');
            cantStock = row.procantstock;
            //data-options
            //label:'Cantidad a comprar:',labelPosition:'top', min:0
            document.getElementById('cicantidad').max = cantStock;
            /* $('#cicantidad').data-options({
                "label": 'Cantidad a comprar:',
                "labelPosition": 'left',
                "max": cantStock, // substitute your own
                "min": 0 // values (or variables) here
            }); */
            console.log(cantStock);
            //document.getElementById('cicantidad').max = cantStock;
            $('#fm1').form('load', row);
            url = 'accion/edit_stock.php?idproducto=' + row.idproducto;
        }
    }

    function guardarCompra() {
        $('#fm1').form('submit', {
            url: url,
            onSubmit: function() {
                return $(this).form('validate');
            },
            success: function(result) {
                var result = eval('(' + result + ')');
                //alert('Volvio servidor');
                if (!result.respuesta) {
                    $.messager.show({
                        title: 'Error',
                        msg: result.errorMsg
                    });
                } else {
                    $('#dlg1').dialog('close');
                    $('#dg').datagrid('reload');
                }
            }
        })
    }

    function destroyProducto() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $.messager.confirm('confirm', 'Seguro desea eliminar el producto?', function(r) {
                if (r) {
                    $.post('accion/destroy_producto.php?idproducto=' + row.idproducto, {
                        idproducto: row.id
                    }, function(result) {
                        //alert('Volvio servidor');
                        if (result.respuesta) {
                            $('#dg').datagrid('reload');
                        } else {
                            $.messager.show({
                                title: 'Error',
                                msg: result.errorMsg
                            });
                        }
                    }, 'json');
                }
            })
        }
    }
</script>

<style type="text/css">
    .producto {
        background-color: #006d31;
        color: white;
    }
</style>

<?php require_once('../templates/footer.php') ?>