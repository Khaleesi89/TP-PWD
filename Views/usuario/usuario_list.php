<?php
require_once('../templates/preheader.php');
$rol = $objSession->getRolPrimo();
?>

<div class="container-fluid p-5 my-1 d-flex justify-content-center usuario">
    <div class="row">
        <div class="col-sm-12">

            <table id="dg" title="Administrador de Usuarios" class="easyui-datagrid" style="width:1200px;height:700px" url="./accion/listar_usuario.php" toolbar="#toolbar" pagination="true" fitColumns="true" singleSelect="true">
                <thead>
                    <tr>
                        <th field="idusuario" width="50">Id</th>
                        <th field="usnombre" width="50">Nombre</th>
                        <th field="usmail" width="50">Mail</th>
                        <th field="usdeshabilitado" width="50">Fecha Deshabilitado</th>
                    </tr>
                </thead>
            </table>

            <div id="toolbar" style="padding:4px">
                <?php
                if ($rol == 'Admin') {
                    echo "<a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-add\" plain=\"true\" onclick=\"newUsuario()\">Nuevo Usuario</a>
                <a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-edit\" plain=\"true\" onclick=\"editUsuario()\">Editar Usuario</a>
                <a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-remove\" plain=\"true\" onclick=\"destroyUsuario()\">Deshabilitar Usuario</a>
                <a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-remove\" plain=\"true\" onclick=\"undestroyUsuario()\">Habilitar Usuario</a>
                <a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-remove\" plain=\"true\" onclick=\"newRol()\">Ver Roles</a>";
                } elseif ($rol == 'Deposito' || $rol == 'Cliente') {
                    echo "<a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-edit\" plain=\"true\" onclick=\"editUsuario()\">Editar Usuario</a>
                <a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-remove\" plain=\"true\" onclick=\"destroyUsuario()\">Deshabilitar Usuario</a>
                <a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-remove\" plain=\"true\" onclick=\"undestroyUsuario()\">Habilitar Usuario</a>
                <a href=\"javascript:void(0)\" class=\"easyui-linkbutton\" iconCls=\"icon-remove\" plain=\"true\" onclick=\"newRolEs()\">Ver Roles</a>";
            }
        ?>
    </div>
    <div id="dlg" class="easyui-dialog" style="width:600px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="POST" novalidate style="margin:0;padding:20px 50px;">
            <h3>Usuario Información</h3>
            <div style="margin-bottom:10px;">
                <input name="usnombre" id="usnombre" class="easyui-textbox" required="true" label="Nombre" style="width:100%;">
            </div>
            <div style="margin-bottom:10px;">
                <input name="uspass" id="uspass" class="easyui-textbox" required="true" label="Password" style="width:100%;">
            </div>
            <div style="margin-bottom:10px;">
                <input name="usmail" id="usmail" class="easyui-textbox" required="true" label="Email" style="width:100%;">
            </div>


        </form>
        <div id="dlg-buttons">
            <a href="javascript:void(0)" class="easyui-button c6" iconCls="icon-ok" onclick="guardarUsuario()" style="width:90px">Aceptar</a>
            <a href="javascript:void(0)" class="easyui-button" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
        </div>
    </div>
</div>

<div id="dlg1" class="easyui-dialog" style="width:600px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg1-buttons'">
    <form id="fm1" method="POST" novalidate style="margin:0;padding:20px 50px;" enctype="multipart/form-data">
        <h3>Información de Roles</h3>
        <?php
        $stringArr = "<script>let arrayF = [";
        foreach ($arrayRoles as $key => $value) {
            $texto = $value->dameDatos();
            $id = $texto['idrol'];
            $rodescripcion = $texto['rodescripcion'];
            $value = 'true';
            echo "<div style=\"margin-bottom:20px\">
                <label for=\"$id\" class=\"textbox-label\">$rodescripcion:</label>
                <input id=rol$id type=\"checkbox\" name=\"rol$id\">
                </div>";
            $stringArr .= "'rol$id',";
        }
        $stringArr = substr($stringArr, 0, -1);
        $stringArr .= '];</script>';
        echo $stringArr;
        ?>
    </form>
    <div id="dlg1-buttons">
        <?php 
            if($rol == 'Admin'){
                echo "<a href=\"javascript:void(0)\" class=\"easyui-button c6\" iconCls=\"icon-ok\" onclick=\"guardarRoles()\" style=\"width:90px\">Aceptar</a>";
            }
        ?>
        
        <a href="javascript:void(0)" class="easyui-button" iconCls="icon-cancel" onclick="javascript:$('#dlg1').dialog('close')" style="width:90px">Cancelar</a>
    </div>
</div>

<script>
    var url;
    var urlr;
    var datos;
    var arralgo;
    var arrkeys;
    var algo;

    function newUsuario() {
        $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nuevo usuario');
        $('#fm').form('clear');
        url = './accion/insertar_usuario.php';
    }

    function cargarDatos(datos) {
        arralgo = Object.values(datos.data);
        arrkeys = Object.keys(datos.data);
        for (key in arrkeys) {
            algo = arrkeys[key];
            if (arralgo[key] == 'true') {
                document.getElementById(algo).click();
            }
        }
    }

    function cargarDatosEs(datos) {
        arralgo = Object.values(datos.data);
        arrkeys = Object.keys(datos.data);
        for (key in arrkeys) {
            algo = arrkeys[key];
            if (arralgo[key] == 'true') {
                document.getElementById(algo).click();
            }
            document.getElementById(algo).disabled = true;
        }
    }

    function newRolEs() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            urlr = row.idusuario;
            $('#dlg1').dialog('open').dialog('center').dialog('setTitle', 'Roles');
            $('#fm1').form('clear');
            $('#fm1').form('load', './accion/roles_usuario?idusuario=' + row.idusuario);
            datos = fetch('./accion/roles_usuario?idusuario=' + row.idusuario, {
                method: "POST",
                body: JSON.stringify(datos),
                headers: {
                    "Content-Type": "application/json"
                }
            }).then((e) => {
                return e.json();
            }).then(data => {
                cargarDatosEs(data);
            });

        }

    }

    function newRol() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            urlr = row.idusuario;
            $('#dlg1').dialog('open').dialog('center').dialog('setTitle', 'Roles');
            $('#fm1').form('clear');
            $('#fm1').form('load', './accion/roles_usuario?idusuario=' + row.idusuario);
            datos = fetch('./accion/roles_usuario?idusuario=' + row.idusuario, {
                method: "POST",
                body: JSON.stringify(datos),
                headers: {
                    "Content-Type": "application/json"
                }
            }).then((e) => {
                return e.json();
            }).then(data => {
                cargarDatos(data);
            });

        }

    }

    function guardarRoles() {
        $('#fm1').form('submit', {
            url: './accion/guardar_roles.php?idusuario=' + urlr,
            onSubmit: function() {
                return $(this).form('validate');
            },
            success: function(result) {
                var result = eval('(' + result + ')');
                if (!result.respuesta) {
                    $.messager.show({
                        title: 'Error',
                        msg: result.errorMsg
                    });
                } else {
                    $('#dlg1').dialog('close');
                }
            }
        })
    }

    function editUsuario() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Editar usuario');
            $('#fm').form('load', row);
            url = './accion/edit_usuario.php?idusuario=' + row.idusuario;
        }
    }

    function guardarUsuario() {
        $('#fm').form('submit', {
            url: url,
            onSubmit: function() {
                return $(this).form('validate');
            },
            success: function(result) {
                var result = eval('(' + result + ')');
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

    function destroyUsuario() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $.messager.confirm('confirm', 'Seguro desea eliminar el usuario?', function(r) {
                if (r) {
                    $.post('./accion/destroy_usuario.php?idusuario=' + row.idusuario, {
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

    function undestroyUsuario() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $.messager.confirm('confirm', 'Seguro desea habilitar el usuario?', function(r) {
                if (r) {
                    $.post('./accion/undestroy_usuario.php?idusuario=' + row.idusuario, {
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
    .usuario {
        background-color: #006d31;
        color: white;
    }
</style>

<?php require_once('../templates/footer.php') ?>