var num_aleatorio;
var urlFechas ="index.php?_module=Gerente&_op=genera_fechas";
var ano_id;
var mes_id;
var array_fechas;
var gid;
var dia_sem;
var site_name;
$(document).ready(function (){
    $("#selecciona_fechas").hide();
    $("#guarda_notificacion").hide();

    $('[type=radio]').click(function(){
        $("#msg").html('');
         $('input:radio:checked').each(function(i, item){
            dia_sem=$(item).val();
        });
        $("#selecciona_fechas").hide();
        $("#guarda_notificacion").hide()
        ano_id=$("#ano_id").val();
        mes_id=$("#mes_id").val();
        gid=$("#gid").val();
        if((ano_id != 0) && (mes_id != 0))
        {
            num_aleatorio = Math.round(Math.random()*(1267485));
            $.get(urlFechas,{gid:gid,ano_id:ano_id,mes_id:mes_id,dia:dia_sem,num_aleatorio:num_aleatorio},function(data){
                $("#selecciona_fechas").html(data);
                $("#selecciona_fechas").show();
                $("#guarda_notificacion").show()
            });
        }
        else
        {
            alert("Favor de seleccionar año, mes y dia");
        }
    });

    $("#mes_id").change(function(){
        $("#msg").html('');
         $('input:radio:checked').each(function(i, item){
            dia_sem=$(item).val();
        });
        $("#selecciona_fechas").hide();
        $("#guarda_notificacion").hide()
        ano_id=$("#ano_id").val();
        mes_id=$("#mes_id").val();
        gid=$("#gid").val();
        if((ano_id != 0) && (mes_id != 0))
        {
            num_aleatorio = Math.round(Math.random()*(1267485));
            $.get(urlFechas,{gid:gid,ano_id:ano_id,mes_id:mes_id,dia:dia_sem,num_aleatorio:num_aleatorio},function(data){
                $("#selecciona_fechas").html(data);
                $("#selecciona_fechas").show();
                $("#guarda_notificacion").show()
            });
        }
        else
        {
            alert("Favor de seleccionar año, mes y dia");
        }
    });


    $("#guarda_notificacion").click(function(){
        if(confirm("Desea guardar las fechas para notificar"))
        {
            site_name=$("#site_name").val();
            $.gritter.add({
                title:site_name,
                text: 'Se han almacenado las notificaciones de e-mail',
                //image:'http://localhost/sf_site/'+site_name+'/img/logo/'+site_name+'.png',
                image:'http://www.pcsmexico.com/salesfunnel/'+site_name+'/img/logo/'+site_name+'.png',
                sticky: false,
                time: ''
            });
        }
        else
            return false;
    })

});