<?php
require_once('../templates/preheader.php');
$rol = $objSession->getRolPrimo();
?>
<div class="container-fluid p-5 my-1 d-flex justify-content-center rol">
    <div class="row">
        <div class="col-sm-12">

            <table id="dg" title="Administrador de Roles" class="easyui-datagrid" style="width:1200px; height:700px" url="./accion/listar_rol.php" toolbar="#toolbar" pagination="true" fitColumns="true" singleSelect="true">
                <thead>
                    <tr>
                        <th field="idrol" width="50">Id</th>
                        <th field="rodescripcion" width="50">Descripción</th>
                    </tr>
                </thead>
            </table>

            <div id="toolbar" style="padding:4px">
                <?php
                if ($rol == 'Admin') {
                    echo "<a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-add\" plain=\"true\" onclick=\"newRol()\">Nuevo Rol</a>
                    <a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-edit\" plain=\"true\" onclick=\"editRol()\">Editar Rol</a>
                    <a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-remove\" plain=\"true\" onclick=\"destroyRol()\">Eliminar Rol</a>";
                }
                ?>
            </div>

            <div id="dlg" class="easyui-dialog" style="width:600px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
                <form id="fm" method="POST" novalidate style="margin:0; padding:20px 50px;">
                    <h3>Rol Información</h3>
                    <div style="margin-bottom:10px;">
                        <input name="rodescripcion" id="rodescripcion" class="easyui-textbox" required="true" label="Nombre" style="width:100%;">
                    </div>
                </form>
                <div id="dlg-buttons">
                    <a href="javascript:void(0)" class="easyui-button c6" iconCls="icon-ok" onclick="guardarRol()" style="width:90px">Aceptar</a>
                    <a href="javascript:void(0)" class="easyui-button" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    var url;

    function newRol() {
        $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nuevo rol');
        $('#fm').form('clear');
        url = 'accion/insertar_rol.php';
    }

    function editRol() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Editar rol');
            $('#fm').form('load', row);
            url = 'accion/edit_rol.php?idrol=' + row.idrol;
        }
    }

    function guardarRol() {
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

    function destroyRol() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $.messager.confirm('confirm', 'Seguro desea eliminar el rol?', function(r) {
                if (r) {
                    $.post('accion/destroy_rol.php?idrol=' + row.idrol, {
                        idrol: row.id
                    }, function(result) {
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
    .rol {
        background-color: #006d31;
        color: white;
    }
</style>

<?php require_once( '../templates/footer.php' ) ?>