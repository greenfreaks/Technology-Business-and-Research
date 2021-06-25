const SERVER = "../../php/plataforma/test/donde_empezar";
let campos, sectores, objetives;
jQuery(document).ready(function ($) {

    //===================================================================
    //=============== Zero State 
    //===================================================================

    $("#q1").hide();
    $("#q2").hide();
    $("#q3").hide();
    $("#results").hide();
    $("#div-finish").hide();
    $("#div-selected_sectores").hide();

    $("#contestar").on("click", function () {
        document.getElementById("progress").style.width = "30%";
        $("#start").hide();
        $("#q1").show("slow");
        $("#progress1").removeClass("disabled");
    });

    //===================================================================
    //=============== 1. Campo de conocimiento 
    //===================================================================

    $.getJSON(SERVER + '/get_disciplinas.php', function (data) {
        campos = data;
        $("#div-disciplina").hide();
        $("#div-subdisciplina").hide();
        $('#select-campo_de_conocimiento').children().remove();
        $('#select-campo_de_conocimiento').append($('<option>', {
            text: 'Seleccione Campo',
            disabled: true,
            selected: true,
        }));
        for (let campo of campos.campos) {
            $('#select-campo_de_conocimiento').append($('<option>', {
                value: campo.id,
                text: campo.cc
            }));
            $('#select-campo_de_conocimiento').formSelect();
        }
    });

    let selected_campo;
    $('#select-campo_de_conocimiento').on('change', function () {
        //alert( this.value );
        $("#div-disciplina").hide();
        $("#div-subdisciplina").hide();
        $("#step2").addClass("disabled");
        selected_campo = campos.campos.find(item => item.id === this.value);

        $('#select-disciplina').children().remove();
        $('#select-disciplina').append($('<option>', {
            text: 'Seleccione Disciplina',
            disabled: true,
            selected: true,
        }));
        for (let disciplina of selected_campo.disciplinas) {
            $('#select-disciplina').append($('<option>', {
                value: disciplina.id,
                text: disciplina.disciplina
            }));
            $('#select-disciplina').formSelect();
        }
        $("#div-disciplina").show('slow');
    });

    let selected_disciplina;
    $('#select-disciplina').on('change', function () {
        //alert( this.value );
        $("#div-subdisciplina").hide();
        $("#step2").addClass("disabled");
        selected_disciplina = selected_campo.disciplinas.find(item => item.id === this.value);

        $('#select-subdisciplina').children().remove();
        $('#select-subdisciplina').append($('<option>', {
            text: 'Seleccione Disciplina',
            disabled: true,
            selected: true,
        }));
        for (let subdisciplina of selected_disciplina.subdisciplinas) {
            $('#select-subdisciplina').append($('<option>', {
                value: subdisciplina.id,
                text: subdisciplina.subdisciplina
            }));
            $('#select-subdisciplina').formSelect();
        }
        $("#div-subdisciplina").show('slow');
    });

    $('#select-subdisciplina').on('change', function () {
        $("#step2").removeClass("disabled");
    });

    $("#step2").on("click", function () {

        if ($("#select-subdisciplina").val() != null) {

            document.getElementById("progress").style.width = "60%";
            $("#q1").hide();
            $("#progress1").addClass("disabled");
            $("#progress2").removeClass("disabled");
            $("#q2").show("slow");

        } else {
            M.toast({
                html: `<span class="red-text">seleccione un campo de conocimiento</span>`
            });
        }

    });


    //===================================================================
    //=============== 2. Objetivos ONU 
    //===================================================================

    $("#q2-fuentes_em").hide();
    $("#q2-viable").hide();

    let selected_objetivos = [];
    $.getJSON(SERVER + '/get_objetivos_onu.php', function (data) {

        if (!(data.error)) {
            objetives = data.objetivos;
            let auxString = ``;

            for (let objetivo of data.objetivos) {
                auxString += `
                <div class="col l3 m4 s12 ${objetivo.color} celda valign-wrapper">
                    <p class="left-align"><label><input type="checkbox" class="filled-in objetivo" value="${objetivo.id}" /><span class="white-text">${objetivo.objetivo}</span></label></p>
                </div>`;
            }

            $("#q2-nec").html(auxString);
            let objetivos = document.querySelectorAll('.objetivo');

            for (let objetivo of objetivos) {

                objetivo.addEventListener('change', function (e) {

                    if (this.checked) {
                        selected_objetivos.push(this.value);

                    } else {
                        selected_objetivos = selected_objetivos.filter(el => el !== this.value);
                    }

                });
            }

        } else {
            console.error("error: " + data.msg);
            M.toast({
                html: '<span class="red-text">⚠ ERROR al recibir sus datos, por favor recargue la pagina e intente de nuevo </span>'
            });
        }

    });

    $('input[type=radio][name=radio_estudio_mercado]').change(function () {
        if (this.value == '1') {
            $("#q2-fuentes_em").hide("slow");
            $("#q2-viable").hide("slow");
        } else {
            $("#q2-fuentes_em").show("slow");
        }

        let ele = document.getElementsByName("radio_fuentes_em");
        for (var i = 0; i < ele.length; i++)
            ele[i].checked = false;
    });

    $('input[type=radio][name=radio_fuentes_em]').change(function () {
        if (this.value == '1') {

            $("#q2-viable").show("slow");
        } else {
            $("#q2-viable").hide("slow");
        }

        let ele = document.getElementsByName("radio_viable");
        for (var i = 0; i < ele.length; i++)
            ele[i].checked = false;
    });

    $("#step3").on("click", function () {

        if (selected_objetivos.length > 0) {
            document.getElementById("progress").style.width = "90%";
            $("#q2").hide();
            $("#progress2").addClass("disabled");
            $("#progress3").removeClass("disabled");
            $("#q3-InfoCompetitividad").hide("slow");
            $("#q3").show("slow");
        } else {
            M.toast({
                html: `<span class="red-text">Seleciona por lo menos un objetivo</span>`
            });
        }

    });

    //===================================================================
    //=================== 3. Sector SCIAN 
    //===================================================================

    let sectores_scian = [];
    $.getJSON(SERVER + '/get_sectores_scian.php', function (data) {
        sectores = data;
        $("#div-subsector").hide();
        $("#div-rama").hide();
        $("#seleccionar-sector-btn").hide();
        $("#agregar-sector-btn").hide();

        $('#select-sector_scian').children().remove();
        $('#select-sector_scian').append($('<option>', {
            text: 'Seleccione Sector',
            disabled: true,
            selected: true,
        }));
        for (let sector of sectores.sectores) {
            $('#select-sector_scian').append($('<option>', {
                value: sector.id,
                text: sector.sector
            }));
        }
        $('#select-sector_scian').formSelect();
    });

    let selected_sector;
    $('#select-sector_scian').on('change', function () {
        //alert( this.value );
        $("#div-subsector_scian").hide();
        $("#div-rama").hide();
        $("#seleccionar-sector-btn").hide();
        $("#agregar-sector-btn").hide();
        selected_sector = sectores.sectores.find(item => item.id === this.value);

        $('#select-subsector_scian').children().remove();
        $('#select-subsector_scian').append($('<option>', {
            text: 'Seleccione subsector',
            disabled: true,
            selected: true,
        }));
        for (let subsector of selected_sector.subsectores) {
            $('#select-subsector_scian').append($('<option>', {
                value: subsector.id,
                text: subsector.subsector
            }));
            $('#select-subsector_scian').formSelect();
        }
        $("#div-subsector").show('slow');
    });

    let selected_subsector;
    $('#select-subsector_scian').on('change', function () {

        $("#div-rama").hide();
        $("#agregar-sector-btn").hide();
        selected_subsector = selected_sector.subsectores.find(item => item.id === this.value);

        $('#select-rama_scian').children().remove();
        $('#select-rama_scian').append($('<option>', {
            text: 'Seleccione Rama',
            disabled: true,
            selected: true,
        }));
        for (let rama of selected_subsector.ramas) {
            $('#select-rama_scian').append($('<option>', {
                value: rama.id,
                text: rama.rama
            }));
            $('#select-rama_scian').formSelect();
        }
        $("#div-rama").show('slow');
    });

    $('#select-rama_scian').on('change', function () {
        $("#seleccionar-sector-btn").show();

    });

    let table_sector = [];
    document.querySelector("#seleccionar-sector-btn").addEventListener("click", function (e) {
        $("#div-sector").hide();
        $("#div-subsector").hide();
        $("#div-rama").hide();
        $("#seleccionar-sector-btn").hide();

        let auxSelected = {
            idsector: $('#select-sector_scian').val(),
            sector: sectores.sectores.find(item => item.id === $('#select-sector_scian').val()).sector,
            idsubsector: $('#select-subsector_scian').val(),
            subsector: selected_sector.subsectores.find(item => item.id === $('#select-subsector_scian').val()).subsector,
            idrama: $('#select-rama_scian').val(),
            rama: selected_subsector.ramas.find(item => item.id === $('#select-rama_scian').val()).rama
        }

        table_sector.push(auxSelected);

        fill_table(table_sector);

        $("#agregar-sector-btn").show();
        $("#div-selected_sectores").show('slow');
        $("#div-finish").show('slow');
    });

    let fill_table = (table_array) => {

        let auxstring = ``;

        for (var [key, item] of table_array.entries()) {
            auxstring += `<tr>
            <td>${item.sector}</td>
            <td>${item.subsector}</td>
            <td>${item.rama}</td>
            <td><a data-elem="${key}" class="btn-floating btn-small waves-effect waves-light red btn-del_sector"><i class="material-icons">delete_forever</i></a></td>
          </tr>`;
        }

        $("#div-selected_sectores").html(`
        <table class="centered striped responsive-table">
            <thead class="white-text">
              <tr>
                  <th>Sector</th>
                  <th>Subsector</th>
                  <th>Rama</th>
                  <th>Quitar</th>
              </tr>
            </thead>
            <tbody>
              ${auxstring}
            </tbody>
      </table>`);

        let botones = document.querySelectorAll('.btn-del_sector');

        for (let boton of botones) {
            boton.onclick = function (e) {
                //                console.log(this.dataset.elem);
                table_sector.splice(this.dataset.elem, 1);
                fill_table(table_sector);

                if (table_sector.length < 1) {
                    $("#div-finish").hide();
                } else {
                    $("#div-finish").show();
                }

            }
        }

    };

    document.querySelector("#agregar-sector-btn").addEventListener("click", function (e) {

        //        document.querySelector('#select-sector_scian').selectedIndex = 0;
        document.querySelector('#select-sector_scian').options[0].selected = true;
        $("#select-sector_scian").formSelect();

        $("#agregar-sector-btn").hide();
        $("#div-sector").show("slow");


        $("#div-finish").hide();
    });

    $('input[type=radio][name=radio_EstudioCompetitividad]').change(function () {
        if (this.value == '0') {

            $("#q3-InfoCompetitividad").show("slow");
        } else {
            $("#q3-InfoCompetitividad").hide("slow");
        }

        let ele = document.getElementsByName("radio_InfoCompetitividad");
        for (var i = 0; i < ele.length; i++)
            ele[i].checked = false;
    });


    //===================================================================
    //=================== Test Finalizado 
    //===================================================================

    let test_results;
    $("#finish").on("click", function () {
        document.getElementById("progress").style.width = "100%";

        $("#recomendacion-CampoConocimiento").hide();
        $("#recomendacion-PropiedadIntelectual").hide();
        $("#recomendacion-TestValorTec").hide();
        $("#recomendacion-TestPotencialInnova").hide();
        $("#recomendacion-EstudioMercado").hide();
        $("#recomendacion-ViabilidadEconomica").hide();
        $("#recomendacion-TestCompetitividad").hide();
        $("#recomendacion-EstudioCompetitividad").hide();

        test_results = objectifyForm($("#answers").serializeArray());
        test_results['campo'] = $("#select-subdisciplina").val();
        test_results['objetivos_onu'] = selected_objetivos;
        test_results['sector_industrial'] = table_sector.map(a => a.idrama);

        //        console.log(test_results);

        //======listas
        if ('radio_contribucion_cc' in test_results) {
            if (test_results.radio_contribucion_cc === '1') {
                $('#recomendacion-TestValorTec').show();
            } else {
                $('#recomendacion-CampoConocimiento').show();
            }
        }

        if ('radio_patente' in test_results) {
            if (test_results.radio_patente === '1') {
                $('#recomendacion-TestValorTec').show();
            } else {
                $('#recomendacion-PropiedadIntelectual').show();
            }
        }

        if ('radio_estudio_mercado' in test_results) {
            if (test_results.radio_estudio_mercado === '1') {
                $('#recomendacion-TestPotencialInnova').show();
            }
        }

        if ('radio_fuentes_em' in test_results) {
            if (test_results.radio_fuentes_em === '0') {
                $('#recomendacion-EstudioMercado').show();
            }
        }

        if ('radio_viable' in test_results) {
            if (test_results.radio_viable === '1') {
                $('#recomendacion-TestPotencialInnova').show();
            } else {
                $('#recomendacion-ViabilidadEconomica').show();
            }
        }

        if ('radio_EstudioCompetitividad' in test_results) {
            if (test_results.radio_EstudioCompetitividad === '1') {
                $('#recomendacion-TestCompetitividad').show();
            }
        }

        if ('radio_InfoCompetitividad' in test_results) {
            if (test_results.radio_InfoCompetitividad === '1') {
                $('#recomendacion-TestCompetitividad').show();
            } else {
                $('#recomendacion-EstudioCompetitividad').show();
            }
        }

        $("#q3").hide();
        $("#markers").hide();
        $("#results").show("slow");
    });

    $("#results-form").submit(function (e) {
        e.preventDefault();

        test_results['email'] = $("#results-form-input-email").val();

        $.ajax({
            type: "POST",
            dataType: 'jsonp',
            url: SERVER + "/send_results.php",
            //            data: test_results + "&email=" + $("#results-form-input-email").val(),
            data: test_results,
            async: false,
            success: function (data) {

                if (!(data.error)) {
                    console.log("success");
                } else {
                    console.error("error: " + data.msg);
                }
            },
            error: function (e) {
                console.error(`ERROR JS: ${e}`);
                M.toast({
                    html: '⚠ Ocurrio un error al enviar sus datos, por favor recargue la pagina e intente de nuevo ⚠'
                });
            },
            beforeSend: function () {
                console.log("loading..");
            },
            complete: function () {
                console.log("complete..");
            }
        });


    });

    console.log("Hola 😁, veo que estas viendo mi codigo, si tienes alguna duda, enviame un correo a leocasdeveloper@gmail.com");
});
