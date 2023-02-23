<?php
//require_once('../../config.php');
require_once('../templates/preheader.php');
$objMenuROl = new MenuRolController();
$lista = $objMenuROl->listarTodo();
?>

<div class="container d-flex justify-content-center mt-5 mb-5">
    <table id="dg" title="Administrador de Roles y Menúes" class="easyui-datagrid" style="width:700px;height:600px" url="accion/listar_menurol.php" toolbar="#toolbar" pagination="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th field="idmr" width="50">Id</th>
                <th field="idmenu" width="50">Id de Menú</th>
                <th field="idrol" width="50">Id de Rol</th>
            </tr>
        </thead>
    </table>
    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMenuRol()">Nueva Relación</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMenuRol()">Editar Relación</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyMenuRol()">Eliminar Relación</a>  
    </div>
    <div id="dlg" class="easyui-dialog" style="width:600px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
    <form id="fm" method="POST" novalidate style="margin:0,padding:20px 50px;">
    <h3>Menu - Rol Información</h3>
    <div style="margin-bottom:10px;">
        <input name="idmenu" id="idmenu" class="easyui-textbox" required="true" label="Menu" style="width:100%;">
    </div>
    <div style="margin-bottom:10px;">
        <input name="idrol" id="idrol" class="easyui-textbox" required="true" label="Rol" style="width:100%;">
    </div>       
    </form>
    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-button c6" iconCls="icon-ok" onclick="guardarMenuRol()" style="width:90px">Aceptar</a>
        <a href="javascript:void(0)" class="easyui-button" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
    </div>
    <script>
        var url;
        function newMenuRol(){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nueva Relación');
            $('#fm').form('clear');
            url='accion/insertar_menurol.php';
        }
        function editMenuRol(){
            var row = $('#dg').datagrid('getSelected');
            if(row){
                $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Editar Relación');
                $('#fm').form('load', row);
                url='accion/edit_menurol.php?idmr='+row.idmr;
            }
        }
        function guardarMenuRol(){
            $('#fm').form('submit', {
                url:url,
                onSubmit:function(){
                    return $(this).form('validate');
                },
                success:function(result){
                    var result=eval('('+result+')');
                    //alert('Volvio servidor');
                    if(!result.respuesta){
                        $.messager.show({
                            title:'Error',
                            msg:result.errorMsg
                        });
                    }else{
                        $('#dlg').dialog('close');
                        $('#dg').datagrid('reload');
                    }
                }
            })
        }
        function destroyMenuRol(){
            var row=$('#dg').datagrid('getSelected');
            if(row){
                $.messager.confirm('confirm', 'Seguro desea eliminar la Relación?', function(r){
                    if(r){
                        $.post('accion/destroy_menurol.php?idproducto='+row.idmr,{idmr:row.id}, function(result){
                            alert('Volvio servidor');
                            if(result.respuesta){
                                $('#dg').datagrid('reload');
                            }else{
                                $.messager.show({
                                    title:'Error',
                                    msg:result.errorMsg
                                });
                            }
                        }, 'json');
                    }
                })
            }
        }
    </script>
        
    </div>
</div>

<?php require_once('../templates/footer.php') ?>