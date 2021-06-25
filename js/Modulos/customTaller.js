jQuery(document).ready(function ($) {

    $("#taller-form").submit(function (e) {
        e.preventDefault();

        if ($('#tos').is(':checked')) {

            /* Get input values from form */
            let values = objectifyForm($("#taller-form").serializeArray());

            values.temas = [];
            $('#temas input:checked').each(function () {
                values.temas.push($(this).attr('value'));
            });

            values.asistentes = [];
            $('#asistentes input:checked').each(function () {
                values.asistentes.push($(this).attr('value'));
            });

            console.log(values);

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "../../../php/init/customTaller.php",
                data: values, 
                success: function (data) {

                    if (!(data.error)) {
                        console.log("success");
                        M.toast({html: 'Datos Recibidos'});
                        
                        $("#modal-RegistroFinalizado").modal('open');
                    } else {
                        console.error("error: " + data.msg);
                        M.toast({html: '<span class="red-text">⚠ ERROR al recibir sus datos, por favor recargue la pagina e intente de nuevo </span>'});
                    }
                },
                error: function(e){
                    console.error(e);
                    M.toast({html: '<span class="red-text">⚠ ERROR al enviar sus datos, por favor recargue la pagina e intente de nuevo </span>'});
                }
            });


        } else {
            M.toast({
                html: '<span class="yellow-text">ℹ Debe de aceptar los terminos y condiciones</span>'
            });
        }

    });

});
