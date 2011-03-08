var email;
var codigo;
var aleatorio;
var nombre;
var apellido_p;
var apellido_m;
var producto;
var existe;
var site_name;
var contacto_id;
var url_codigo="index.php?_module=Directorio&_op=ajax-codigo_campana";
var url_existe="index.php?_module=Directorio&_op=ajax-contacto_repetido";
$(document).ready(function(){
    //Checar correo electronico
    $("#email").blur(function(){
        email=$("#email").val();
        if(email.length > 0)
        {
            if(!valEmail(email))
            {
                alert("Favor de proporcinar un correo valido");
                return false;
            }
        }
    })

    //Checar codigo de campana
    $("#codigo_campana").blur(function(){
        aleatorio = Math.round(Math.random()*1000);
        codigo=$("#codigo_campana").val();
        codigo=codigo.toUpperCase();
        if(codigo.length > 0)
        {
            $.get(url_codigo,{codigo_campana:codigo,aleatorio:aleatorio},function(data){
                if(data!='ok')
                {
                    $('#codigo_campana').select();
                    $('#codigo_campana').focus();
                    $("#valida").html(data);
                }
                else
                    $("#valida").html('');
            })
        }
        else
        {
            $("#valida").html('');
        }
    })

    // boton guardar datos
    $("#buttonguardar").click(function(){
        var f = document.contacto;
        nombre    =$("#nombre").val();
        apellido_p=$("#apellido_paterno").val();
        apellido_m=$("#apellido_materno").val();
        producto  =$("#listVehicle").val();
        site_name=$("#site_name").val();

        if (nombre == '')
        {
            alert("Ingrese un nombre");
            return false;
        }
        if (apellido_p == '')
        {
            alert("Ingrese un apellido");
            return false;
        }
        if ( ($("#tel_casa").val() == '') && ($("#tel_oficina").val()== '') && ($("#tel_movil").val() == '') && ($("#tel_casa_2").val()== '') && ($("#tel_oficina_2").val() == '') && ($("#tel_movil_2").val() == '' ) )
        {
            alert("Ingrese por lo menos un teléfono");
            return false;
        }
        if( $("#prioridad_contacto").val() == '')
        {
            alert("Seleccione una opción en prioridad");
            return false;
        }
        if ($("#no_contactar").val() == -1)
        {
            alert("Seleccione una opción para contactar");
            return false;
        }
        if ($("#origen").val() == '')
        {
            alert("Seleccione una fuente para el contacto");
            return false;
        }
        if (producto == 0)
        {
            alert("Ingrese un Producto");
            return false;
        }
        aleatorio = Math.round(Math.random()*1000);        
        titulo="El prospecto ya se encuentra registrado, desea guardar el registro";
        contacto_id=$("#contacto_id").val() ;
        if( contacto_id.length > 0)
            titulo="Desea actualizar la información del prospecto";
        existe=0;
        if( (nombre.length > 0) && (apellido_p.length > 0) && (producto > 0))
        {
            $.get(url_existe,{nom:nombre,ap:apellido_p,am:apellido_m,mod:producto,aleatorio:aleatorio},function(data){
                existe=data;
                if(existe == 0)
                    titulo="Desear guardar los datos del prospecto";
                if(confirm(titulo))
                {
                    $.gritter.add({
                        title:site_name,
                        text: 'El prospecto '+nombre+' '+apellido_p+' ha sido registrado',
                        //image:'http://localhost/sf_site/'+site_name+'/img/logo/'+site_name+'.png',
                        image:'http://www.pcsmexico.com/salesfunnel/'+site_name+'/img/logo/'+site_name+'.png',
                        sticky: false,
                        time: '6000'
                    });
                    document.contacto.action = "index.php";
                    document.contacto.guarda.value = "1";
                    document.contacto.method = "POST";
                    document.contacto.submit();
                }
                else
                {
                    alert('El prospecto no ha sido dado de alta');
                }
            });
        }
    })
    //fin de jquery
});

function valEmail(txt)
{
    var b=/^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/
    return b.test(txt)
}

function capsall(theForm){
    var els = theForm.elements;
    for(i=0; i<els.length; i++){
        switch(els[i].type){
            case "text":
                if (els[i].name == "email")
                    break;
                els[i].value= els[i].value.toUpperCase();
                break;
        }
    }
}

function analizar_rfc(rfc)
{
    if ((rfc.value.length == 13)||((rfc.value.length == 14)&&(rfc.value[10] == "-")) ||rfc.value.length == 10)
    {

        var bday = rfc.value.substr(4,6);
        var ano = '19'+bday.substr(0,2);
        var mes = bday.substr(2,2);
        var dia = bday.substr(4,2);
        var fecha_nac=dia+'-'+mes+'-'+ano;
        /*document.contacto.dia.selectedIndex = dia;
        document.contacto.mes.selectedIndex = mes;
        for (i = 0; i < document.contacto.ano.length; i++)
            if (document.contacto.ano.options[i].value == ano)
                document.contacto.ano.selectedIndex = i;*/
        document.contacto.fecha_de_nacimiento.value=fecha_nac;
    }
}

