const SERVER = "../../../php/init";

jQuery(document).ready(function ($) {

    var modales = M.Modal.init(document.querySelectorAll('.modal'), {
        dismissible: false
    });

    let el = document.createElement('script');
    el.type = 'application/ld+json';

    let jsonLd = {
        "@context": "http://www.schema.org",
        "@graph": []
    };

    let calendario;
    let taller_selected;
    $.ajax({
        type: "GET",
        url: SERVER + "/get_calendario.php",
        dataType: 'json',
        success: function (data) {
            console.log("data received");

            if (!(data.error)) {

                if (data.eventos.length == 0) {
                    $("#talleres").html(
                        `<div class="row" >
                            <div class="col s12 center">
                                <h1>Actualmente no existen cursos o talleres programados.</h1>
                            </div>
                        </div>`);
                } else {
                    calendario = data.eventos;
                    for (let event of data.eventos) {

                        let fecha = new Date(event.fecha_inicio);
                        //                        let meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                        let meses = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
                        let bigdate = `${fecha.getDate()}/${meses[fecha.getMonth()]}`;

                        $("#talleres").append(`
                            <div class="row">
                                <div class="col s12">
                                    <div class="card white">
                                        <div class="card-content white">
                                            <div class="row">
                                                <div class="col l3 s12 cta-bg white-text center">
                                                    <h2>${bigdate}</h2>
                                                </div>
                                                <div class="col l9 s12">
                                                    <h4 class="negritas">${event.evento}</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                 <div class="col s12">
                                                    <p><span class="negritas">Descripción: </span>${event.descripcion}</p>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col s12 l4">
                                                    <p><span class="negritas">Fechas: </span>${event.fi} hrs al ${event.ff} hrs</p>
                                                </div>
                                                <div class="col s12 l4">
                                                    <p><span class="negritas">Impartido por: </span>${event.impartido_por}</p>
                                                </div>
                                                <div class="col s12 l4">
                                                    <p><span class="negritas">Sede: </span>${event.sede}</p>
                                                </div>
                                            </div>
                                            <div class="row hide-on-med-and-down">
                                                <div class="col s6 l4">
                                                    <p><span class="negritas">Dirección: </span>
                                                    <a target="_blank" href="https://www.google.com/maps/search/${event.sede} ${event.lugar}">${event.lugar}</a>
                                                    </p>
                                                </div>
                                                <div class="col s6 l4" id="">
                                                    <p><span class="negritas">Público objetivo: </span>${event.publico_objetivo}</p>
                                                </div>
                                                <div class="col s6 l4" id="">
                                                    <p><span class="negritas">Costos: </span>${event.precio}
                                                        <p>
                                                </div>
                                            </div>
                                            <div class="row center hide-on-large-only">
                                                <a href="${event.link}" target="_blank" class="waves-effect waves-light texto-azul-tbr">Más información</a><br><br>
                                                <a href="#modal-inscripcion" class="waves-effect waves-light btn red modal-trigger"><i class="material-icons right">check_circle</i>Inscripciones</a>
                                            </div>
                                        </div>
                                        <div class="card-action hide-on-med-and-down">
                                            <a href="${event.link}" target="_blank" class="waves-effect waves-light texto-azul-tbr">Más información</a>
                                            <a data-id="${event.idcalendario}" href="#modal-inscripcion" class="btn-inscripcion waves-effect waves-light btn red right modal-trigger"><i class="material-icons right">check_circle</i>Inscripciones</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);

                        jsonLd["@graph"].push({
                            "@type": "Event",
                            "name": event.evento,
                            "performer": {
                                "@type": "person",
                                "name": event.impartido_por
                            },
                            "url": event.link,
                            "description": event.descripcion,
                            "startDate": event.fi,
                            "endDate": event.ff,
                            "audience": event.publico_objetivo,
                            "organizer": "Tenchnology Business & Reseach",
                            "location": {
                                "@type": "Place",
                                "address": {
                                    "@type": "PostalAddress",
                                    "streetAddress": event.lugar,
                                    "addressLocality": event.sede,
                                    "addressCountry": "MX"
                                }
                            },
                            "offers": {
                                "@type": "Offer",
                                "description": event.precio,
                                "url": event.link_pagar,
                                //                                    "price": "9.99",
                                "priceCurrency": "mxn"
                            }
                        });

                    }

                    let botones = document.querySelectorAll('.btn-inscripcion');

                    for (let boton of botones) {
                        boton.onclick = function (e) {
                            taller_selected = calendario.find(x => x.idcalendario == $(this).data("id"));
                        }
                    }

                    console.log(JSON.stringify(jsonLd));
                    el.text = JSON.stringify(jsonLd);
                    document.querySelector('head').appendChild(el);


                }

            } else {
                console.error(`ERROR PHP: ${data.msg}`);
                M.toast({
                    html: '<span class="red-text">⚠ ERROR: datos no recibidos <br>Por favor recargue la pagina e intente de nuevo </span>'
                });
            }
        },
        error: function (e) {
            console.error(`ERROR JS`, e);
            M.toast({
                html: '<span class="red-text">⚠ ERROR: no se enviaron los datos<br> Por favor recargue la pagina e intente de nuevo </span>'
            });
        },
        beforeSend: function () {
            console.log("loading events..");
        },
        complete: function () {
            console.log("complete events..");
        }
    });

    $("#inscripcion-form").submit(function (e) {

        e.preventDefault();

        var form = objectifyForm($("#inscripcion-form").serializeArray());
        form['idcalendario'] = taller_selected.idcalendario;

        if (("tos" in form) && form.tos === "on") {
            $.ajax({
                type: "POST",
                url: SERVER + "/registro_taller.php",
                data: form,
                success: function (data) {

                    if (!(data.error)) {

                        $("#btn-pagar").attr("href", taller_selected.link_pagar);
                        $('#modal-inscripcion').modal('close');
                        $('#modal-pagar').modal('open');

                        $("#inscripcion-form")[0].reset();

                    } else {
                        console.error("error: " + data.msg);
                        M.toast({
                            html: `<span class="red-text">Ocurrio un error al recibir tus datos, ponte en contacto con nosotros</span>`
                        });
                    }
                }
            });

        } else {
            alert("Debe aceptar los términos y condiciones.");
        }

    });




});
