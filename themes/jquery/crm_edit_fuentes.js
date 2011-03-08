/*
 * Script que sirve  para administrar el catalogo de fuentes
 */

var urlFilterFuentes="index.php?_module=Filtros&_op=filtroFuentes";
var url_script_edit ="index.php?_module=Catalogos&_op=edit_fuentes";
var padre_id;
var origen_id;
var fuente_padre;
var fecha_ini;
var fecha_fin;
var fuente;
var zona_id;
var id_gid;
var nm_gid;
var origen;
var aleatorio;
$(document).ready(function (){
    $("#padre_id").show();
    $('#hijo_id_1').hide();
    $('#hijo_id_2').hide();
    $('#hijo_id_3').hide();
    $('#hijo_id_4').hide();

// las siguientes funciones es para la busquedad de fuentes
    $("#actualizar_fuente").click(function(){
        aleatorio = Math.round(Math.random()*1000);
        origen_id = $("#origen_id").val();
        origen= $("#origen").val();
        fuente   = $("#fuente").val();
        // decido de quien va hacer padre de la nueva fuente
        if( (fuente != '') && (origen_id != 0) )
        {
            $.post(url_script_edit,{opc:1,padre_id:origen_id,fuente:fuente,aleatorio:aleatorio},function(data){
                if(data == 'Actualizado')
                {
                    $("#resultado").css({color: "#04B45F", background: "#FFFFFF"});
                    $("#resultado").html(data);
                    location.href = "index.php?_module=Catalogos&_op=find&origen="+origen+"&buscar_fuentes=1";
                }
                else
                {
                    $("#resultado").css({color: "#FE2E2E", background: "#FFFFFF"});
                    $("#resultado").html(data);
                }
            });
         }
        else
        {
             alert("Favor de llenar todos los campos, son obligatorios");
        }

    })


    $("#padre_id").change(function(event){
        if($("#padre_id").val() > 1)
        {
            displayListChilds(event,'padre_id','hijo_id_1');
        }
        else
        {
            valor=0;
            $('#hijo_id_1').hide();
            $('#hijo_id_2').hide();
            $('#hijo_id_3').hide();
            $('#hijo_id_4').hide();
            $("#origen").val(valor);
        }
    });

    $("#hijo_id_1").change(function(event){
        if($("#hijo_id_1").val() >1){
            displayListChilds(event,'hijo_id_1','hijo_id_2');
        }
    });

$("#hijo_id_2").change(function(event){
    if($("#hijo_id_2").val() != 0){
        displayListChilds(event,'hijo_id_2','hijo_id_3');
    }
});

$("#hijo_id_3").change(function(event){
    if($("#hijo_id_3").val() != 0){
        displayListChilds(event,'hijo_id_3','hijo_id_4');
    }
});


function displayListChilds(event,div_padre,div_hijo)
{
    var valoractual=$('#'+div_padre).val()
    if($('#'+div_padre).val() > 0)
    {
        $.get(urlFilterFuentes,{id:$('#'+div_padre).val()},function(data){
            if(data.length > 0)
            {
                $("#"+div_hijo).html(data);
                $("#"+div_hijo).show();
                $("#origen").val(valoractual);
            }
            else
            {
                $("#"+div_hijo).hide();
                $("#origen").val(valoractual);
            }
        })
    }
}


});


function Bloquea_Fuentes(id,padre_id)
{
    $.get(url_script_edit,{opc:2,padre_id:id},function(data){
        $("#resultado").html(data);
        location.href = "index.php?_module=Catalogos&_op=find&padre_id="+padre_id+"&buscar_fuentes=1";
    })
}
function Desbloquea_Fuentes(id,padre_id)
{
    $.get(url_script_edit,{opc:3,padre_id:id},function(data){
        $("#resultado").html(data);
        location.href = "index.php?_module=Catalogos&_op=find&padre_id="+padre_id+"&buscar_fuentes=1";
    })
}
function Elimina_Fuentes(id,padre_id)
{
    if(confirm("Esta seguro de eliminar la fuente"))
    {
        $.get(url_script_edit,{opc:4,padre_id:id},function(data){
            $("#resultado").html(data);
            location.href = "index.php?_module=Catalogos&_op=find&padre_id="+padre_id+"&buscar_fuentes=1";
        })
    }
}