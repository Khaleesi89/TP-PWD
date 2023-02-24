<?php
require_once('../templates/preheader.php');

?>

<div class="container d-flex justify-content-center mt-5 mb-5">
    <table id="dg" title="Administrador de Compras" class="easyui-datagrid" style="width:700px;height:600px" url="./accion/listar_compra.php" toolbar="#toolbar" pagination="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th field="idcompra" width="50">Id Compra</th>
                <th field="cofecha" width="50">Fecha</th>
                <th field="idusuario" width="50">Id Usuario</th>
            </tr>
        </thead>
    </table>
    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyCompra()">Eliminar Compra</a>  
    </div>
    <div id="dlg" class="easyui-dialog" style="width:600px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
    <form id="fm" method="POST" novalidate style="margin:0;padding:20px 50px;">
    <h3>Compra informacion</h3>
    
    <div style="margin-bottom:10px;">
        <input name="idusuario" id="idusuario" class="easyui-textbox" required="true" label="Id Compra" style="width:100%;">
    </div>    
    </form>
    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-button c6" iconCls="icon-ok" onclick="guardarCompra()" style="width:90px">Aceptar</a>
        <a href="javascript:void(0)" class="easyui-button" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
    </div>
    <script>
        var url;
        
        function guardarCompra(){
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
        function destroyCompra(){
            var row=$('#dg').datagrid('getSelected');
            if(row){
                $.messager.confirm('confirm', 'Seguro desea eliminar el compra?', function(r){
                    if(r){
                        $.post('accion/destroy_compra.php?idcompra='+row.idcompra,{idcompra:row.id}, function(result){
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
<style>
    


</style>
<?php require_once('../templates/footer.php') ?>