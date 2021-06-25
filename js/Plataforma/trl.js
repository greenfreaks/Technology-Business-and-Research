const SERVER = "../../php/plataforma/TRL";
let n_questions = 0;
let t_percentage = 0;
let sections;
let projectID;

jQuery(document).ready(function ($) {


    $.getJSON(SERVER + '/get_trl_sections.php', function (data) {
        //    $.getJSON('../../php/plataforma/get_trl_sections.json', function (data) {
        sections = data.sections;

        for (let s of sections) {
            n_questions += s.section_questions.length;
        }

        for (let [index, section] of sections.entries()) {
            $("#secciones").append(
                fillsection(section.section_id, section.secction_title, section.secction_description, section.section_questions, index === 0 ? true : false, index === sections.length - 1 ? true : false)
            );
        }
    });


    $("#top").hide();
    $("#secciones").hide();
    $("#resultados").hide();

    let loginModal = M.Modal.init(document.querySelector('#modal-login'), {
        dismissible: false,
        opacity: 0.8
    });

    loginModal.open();

    let logindata;

    $("#login-form").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: SERVER + "/trl_login.php",
            data: $("#login-form").serialize(), // serializes the form's elements.
            success: function (data) {
                if (!(data.error)) {
                    console.log("success");
                    loginModal.close();
                    $("#pdf-form-input-mail").val($("#login-form-input-mail").val());
                    console.log(data.data.id);
                    localStorage.setItem("id", data.data.id);
                } else {
                    M.toast({
                        html: `<h5 class="red-text">Usuario y/o contraseña incorrecta</h5>`
                    });
                    loginModal.open();
                    console.error("error: " + data.msg);
                }
            },
            error: function (e) {
                M.toast({
                    html: `<h4 class="red-text">Error al enviar los datos</h4>`
                });
                console.error(e);
            }
        });

    });

    let formObject;

    $("#inicio-form").submit(function (e) {

        e.preventDefault();
        formObject = objectifyForm($("#inicio-form").serializeArray());

        console.log(`proyecto iniciado ${formObject.project_name}`);
        formObject['startTime'] = new Date().toISOString().slice(0, 19).replace('T', ' ');
        formObject['userID'] = localStorage.getItem("id");

        $("#proyect-name").html(formObject.project);
        $("#trl_description").hide();
        $("#cta_start_trl").hide();

        $("#project_title").html(formObject.project);
        $("#top").show("slow");

        $("#secciones").show("slow");
        $(".trl_section").hide();
        $("#secion_actual").html("Sección: " + sections[0].secction_title);
        $("#descripcion_seccion_actual").html(`<strong>Descripción:</strong> ${sections[0].secction_description}`);
        $("#content_glosario").html(fillglosary(sections[0].glosary));
        $("#s1").fadeIn();
        document.getElementById("progress").style.width = "0%";

    });

    $("#secciones").on("click", ".goto-btn", function () {
        //        console.log(`go to ${$(this).data("goto")}`);
        $(".trl_section").hide();

        t_percentage = 0;
        for (let i = 0; i < $(this).data("goto") - 1; i++) {
            t_percentage += sections[i].section_questions.length;
        }

        let seccion_actual = sections.find(x => x.section_id == $(this).data("goto"));
        $("#secion_actual").html("Sección: " + seccion_actual.secction_title);
        $("#descripcion_seccion_actual").html(`<strong>Descripción:</strong> ${seccion_actual.secction_description}`);
        $("#content_glosario").html(fillglosary(seccion_actual.glosary));


        document.getElementById("progress").style.width = (t_percentage * 100) / n_questions + "%";

        $("#progress-no").html(Math.round((t_percentage * 100) / n_questions) + "%");
        $("#s" + $(this).data("goto")).fadeIn();
    });

    $("#secciones").on("click", ".finish", function () {

        var selected = [];
        $('#secciones input:checked').each(function () {
            selected.push($(this).attr('value'));
        });

        if (selected.length < 5) {
            M.toast({
                html: `<span class="red-text">Respuestas insuficientes para obtener un resultado</span><br><span class="red-text">Verifique sus respuestas</span>`
            });
        } else {

            formObject['answers'] = selected;
            formObject['finishTime'] = new Date().toISOString().slice(0, 19).replace('T', ' ');

            $.ajax({
                type: 'POST',
                url: SERVER + '/get_trl_result.php',
                dataType: 'json',
                data: formObject,
                success: function (resp) {
                    if (resp.error) {
                        console.error(resp.msg);
                        M.toast({
                            html: `<span class="red-text">Error: Datos no recibidos</span>`
                        });
                        alert("Tuvimos un problema al recibir tus datos, por favor ponte en contacto con nosotros");
                    } else {
                        //                    console.log(resp);
                        //                    console.log(resp.resultado);
                        $("#secciones").hide();
                        $("#top").hide();
                        $("#result-level").html(resp.resultado.Nivel);
                        projectID = resp.projectID;
                        //                    console.table(resp.desglose);

                        let tableRows = ``;
                        for (let r of resp.desglose) {
                            tableRows += `<tr>
                                    <td>${r.categoria}</td>
                                    <td>${r.aspLogrados} de ${r.aspCat}</td>
                                    <td>
                                        <div class="progress">
                                          <div class="determinate" style="width: ${r.porcentaje}%"></div>
                                        </div>
                                    </td>
                                  </tr>`;
                        }

                        let tablaDesgloce = `<table>
                                        <thead class="white-text">
                                          <tr>
                                              <th>Categoria</th>
                                              <th>Reactivos</th>
                                              <th>Porcentaje</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          ${tableRows}
                                        </tbody>
                                      </table>`;
                        $("#tableDesglose").html(tablaDesgloce);
                        $("#resultados").show("slow");
                        $("#email-sent").hide();

                        if (resp.resultado.Nivel === "0") {
                            alert("Su resultado final fue 0, para mas detalles le sugerimos solicitar su reporte ingresando su nombre y correo en el formulario al final de esta página");
                            M.toast({
                                html: `<h4 class="red-text">Resultado final cero</h4>`
                            });
                        }

                    }
                },
                error: function (e) {
                    console.error(e);
                },
                beforeSend: function () {
                    console.log("loading..");
                },
                complete: function () {
                    console.log("complete..");
                }
            });

        }


    });

    $("#pdf-form").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: SERVER + "/set_trl_email.php",
            data: 'projectID=' + projectID + '&' + $("#pdf-form").serialize(), // serializes the form's elements.
            success: function (data) {
                if (!(data.error)) {
                    $("#pdf-form").hide();
                    console.log("success");
                    $("#email-sent").show();
                    M.toast({
                        html: `<span class="green-text darken-2-text">Reporte enviado</span>`
                    });
                } else {
                    console.error("error: " + data.msg);
                }
            },
            error: function (e) {
                console.error(e);
            },
            beforeSend: function () {
                console.log("loading..");
            },
            complete: function () {
                console.log("complete..");
            }
        });

    });

});

function fillsection(section_id, secction_title, secction_description, section_questions, is_first, is_last) {

    let qs = "";

    for (let q of section_questions) {
        qs += `<li class="collection-item">
                        <label>
                            <input type="checkbox" value="${q.id}"/>
                            <span>${q.q}</span>
                        </label>
                    </li>`;
    }

    let prev_btn = is_first ? `` : `<a href="#top" data-goto="${parseInt(section_id)-1}" class="waves-effect waves-light btn left goto-btn"><i class="material-icons left">arrow_left</i>anterior</a>`;

    let next_btn = is_last ? `<a href="#top" class="waves-effect waves-light btn right green finish"><i class="material-icons right">send</i>Finalizar</a>` : `<a href="#top" data-goto="${parseInt (section_id)+1}" class="waves-effect waves-light btn right goto-btn"><i class="material-icons right">arrow_right</i>Siguiente</a>`;



    //    <div class="row">
    //                <div class="col s12 ">
    //                    <strong>Descripción:</strong>
    //                    <p> ${secction_description}</p>
    //                </div>
    //            </div>

    return `<div class="section trl_section" id='s${section_id}'>
        <div class="container">
            <div class="row">
                <ul class="collection">
                    ${qs}
                </ul>
            </div>
            <div class="row">
                <div class="col s6 ">
                    ${prev_btn}
                </div>
                <div class="col s6 ">
                    ${next_btn}
                </div>
            </div>
        </div>
    </div>`;
}

function fillglosary(dict) {
    let aux_string = ``
    for (let concept of dict) {
        aux_string += `<li class="collection-item">
                            <strong>${concept.concepto}</strong>
                            <p>${concept.definicion}
                            </p>
                        </li>`;
    }

    return aux_string;
}
