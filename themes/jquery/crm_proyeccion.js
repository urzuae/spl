var cantidad;
var id_meta;
var num_aleatorio;
var site_name;
var host_name;
var urlEliminaMeta ="index.php?_module=Gerente&_op=elimina_meta";
var url_meta="index.php?_module=Gerente&_op=proyeccion";
var tmp;
var f;
$(document).ready(function (){

    $("#todos_meses").click(function(event){
        $("#meses_id option").attr('selected',true);
    });
    $("#des_todos_meses").click(function(event){
        $("#meses_id option").attr('selected',false);
    });


    $("#todos_uids").click(function(event){
        $("#id_user option").attr('selected',true);
    });

    $("#des_todos_uids").click(function(event){
        $("#id_user option").attr('selected',false);
    });

     $("#guarda_proyeccion").click(function(){
         site_name=$("#site_name").val();
         cantidad=$("#cantidad").val();
         cantidad=cantidad.replace('$','');
         cantidad=cantidad.replace(',','');
         if( (cantidad > 0) &&  ($("#meses_id").val() != null) && ($("#id_user").val() != null))
         {
            if(confirm("Desea guardar los datos de la proyeccion"))
            {
                $.post(url_meta,{cantidad:cantidad,id_user:$("#id_user").val(),ano_id:$("#ano_id").val(),meses_id:$("#meses_id").val(),guarda_proyeccion:1},function(data){
                    $("#cantidad").val('0');
                    $("#meses_id option").attr('selected',false);
                    $("#id_user option").attr('selected',false);
                    $.gritter.add({
                        title:site_name,
                        text: data,
                        image:'http://www.pcsmexico.com/salesfunnel/'+site_name+'/img/logo/'+site_name+'.png',
                        sticky: false,
                        time: '5000'
                    });
                })
            }
         }
        else
        {
            alert("Favor de llenar todos los campos, son obligatorios");
            return false;
        }
     });
});

function elimina_meta(gid,uid,ano,mes)
{
    if(confirm("Desea eliminar la meta"))
    {
        num_aleatorio = Math.round(Math.random()*(153650));
        $.get(urlEliminaMeta,{gid:gid,user_id:uid,ano:ano,mes:mes,num_aleatorio:num_aleatorio},function(data){
            alert(data);
            location.href="index.php?_module=Gerente&_op=consulta_proyeccion&ano_id="+ano;
        });
    }
}