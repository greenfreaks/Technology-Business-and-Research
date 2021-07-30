let elems = {};
const server = "http://localhost/tbr/php/init/";
//const server = "http://tecnotransfer.com.mx/php/init/";
(function ($) {
    $(function () {

        let btn_whatsapp = `<li><a href="https://api.whatsapp.com/send?phone=527797966790&text=Buenas tardes, me gustaría solicitar información" target="_blank" class="btn-floating green tooltipped" data-position="left" data-tooltip="Pregúntanos"> <img class="fab-img" src="http://www.techbusiness.com.mx/img/social-networks/whatsapp-logo.svg"></a></li>`;

        let btn_fb = `<li><a href="https://www.facebook.com/TechBusinessMx" target="_blank" class="btn-floating blue tooltipped" data-position="left" data-tooltip="Síguenos"> <img class="fab-img" src="http://www.techbusiness.com.mx/img/social-networks/facebook-logo.png"></a></li>`;

        let btn_messenger = `<li><a href="https://m.me/TechBusinessMx/" target="_blank" class="btn-floating blue tooltipped" data-position="left" data-tooltip="Pregúntanos"> <img class="fab-img" src="http://www.techbusiness.com.mx/img/social-networks/messenger.png"></a></li>`;

        let floatingBtn =
            `<div id="featureHelp" class="tap-target red no-autoinit" data-target="menu">
                <div class="tap-target-content white-text">
                  <h5>Contactanos</h5>
                  <p>Has click en este boton para ver todas las formas de contactarnos</p>
                </div>
            </div>
            <div id="menu" class="fixed-action-btn">
                <a id="social-fab" class="btn-floating btn-large pulse">
                    <i data-position="left" data-tooltip="Asistencia en línea" class="large material-icons box tooltipped">help_outline</i>
                </a>
                <ul>
                    ${btn_whatsapp}
                    ${btn_fb}
                    ${btn_messenger}
                    <li class="hide-on-med-and-up"><a href="tel:017797966790" class="btn-floating green tooltipped" data-position="left" data-tooltip="Llamanos 7797966790"> <i class="large material-icons box">phone</i></a></li>
                    <li><a href="mailto:contacto@techbr.com.mx" class="btn-floating purple tooltipped" data-position="left" data-tooltip="Escríbenos"> <i class="large material-icons box">mail</i></a></li>
                </ul>
            </div>`;

        $(floatingBtn).appendTo('body');

        let loadingModalString =
            `<div id="loading" class="modal">
                <div class="modal-content center">
                  <h4>Espere un momento</h4>
                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                </div>
              </div>`;

        $(loadingModalString).appendTo('body');

        let registerModalString = `<!-- Modal registro -->
        <div id="modal-registro" class="modal modal-fixed-footer">
            <form id="registro-form" data-taller="">
                <div class="modal-content">
                    <h4 class="center">Registro de correo</h4>
                    <p>Por favor ingresa los siguientes datos para registrarte en nuestra lista de interesados y así poder comunicarnos contigo.</p>
                    <div class="row">
                        <div class="input-field col l6 s12">
                            <i class="material-icons prefix">mail</i>
                            <input id="registro-form-input-mail" name="mail" type="email" class="validate" required>
                            <label for="registro-form-input-mail">Email</label>
                            <span class="helper-text" data-error="Ingrese un email valido" data-success="Email valido">No olvide el simbolo @</span>
                        </div>
                        <div class="input-field col l6 s12">
                            <i class="material-icons prefix">account_circle</i>
                            <input id="registro-form-input-name" name="name" type="text" class="validate" required>
                            <label for="registro-form-input-name">Nombre</label>
                            <span class="helper-text" data-error="Minimo de caracteres" data-success="Nombre valido">Por lo menos 5 caracteres</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
                    <button id="registro-form-btn-submit" class="btn waves-effect waves-light" type="submit" name="action">Enviar
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </form>
        </div>`;

        $(registerModalString).appendTo('body');

        M.AutoInit();

        //        elems['ayudaTarget'] = M.TapTarget.init(document.querySelectorAll('.tap-target'), {});
        let ayudaTarget = M.TapTarget.init(document.querySelector('#featureHelp'), {});

        elems['loadingModal'] = M.Modal.init(document.querySelector('#loading'), {
            dismissible: false,
        });

        elems['registerModal'] = M.Modal.init(document.querySelector('#modal-registro'), {
            dismissible: true,
        });

        elems['interesModal'] = M.Modal.init(document.querySelector('#modal-interes'), {
            dismissible: true,
        });


        $('.slider').slider();

        indeterminate_checkbox();

        [].forEach.call(document.getElementsByClassName(".indeterminate-checkbox"), function (element) {
            element.addEventListener("click", function () {
                if (element !== null) element.indeterminate = true;
            });
        });


        /*$(window).scroll(function () {

            if ($(window).scrollTop() > 150) {
                $('#indexnav').addClass('blue-indexnav');
                $('#indexnav').removeClass('indexnav');

            } else {
                $('#indexnav').removeClass('blue-indexnav');
                $('#indexnav').addClass('indexnav');
            }

        });*/

        $("#notificame-form").submit(function (e) {
            e.preventDefault();
            console.log($("#notificame-form").serialize());
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: server + "notificame-form.php",
                data: $("#notificame-form").serialize(), // serializes the form's elements.
                success: function (data) {

                    if (!(data.error)) {
                        console.log("success");
                        M.toast({
                            html: 'Se ha registrado correctamente a nuestro boletín.'
                        });
                    } else {
                        console.error("error: " + data.msg);
                        M.toast({
                            html: '⚠ Ocurrio un error al enviar sus datos, por favor recargue la pagina e intente de nuevo ⚠'
                        });
                    }
                }
            });

        });

        $(".register-btn").on("click", function () {
            console.log("click");
            $("#registro-form").data("interes", $(this).data("interes"));
            elems.registerModal.open();
        });

        $("#registro-form").submit(function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: server + "registro-form.php",
                data: $("#registro-form").serialize() + "&interes=" + $(this).data("interes"), // serializes the form's elements.
                success: function (data) {
                    if (!(data.error)) {
                        console.log("success");
                        elems.registerModal.close();
                        M.toast({
                            html: `Registro correcto, pronto nos comunicaremos con usted.`
                        });
                        ayudaTarget.open();
                    } else {
                        console.error("error: " + data.msg);
                        M.toast({
                            html: '⚠ Ocurrio un error al enviar sus datos, por favor recargue la pagina e intente de nuevo ⚠'
                        });
                    }
                }
            });

        });

        $(".btn-help").on("click", function () {
            //            console.log("click");
            ayudaTarget.open();
        });


    }); // end of document ready
})(jQuery); // end of jQuery name space

function objectifyForm(formArray) { //serialize data function

    var returnArray = {};
    for (var i = 0; i < formArray.length; i++) {
        returnArray[formArray[i]['name']] = formArray[i]['value'];
    }
    return returnArray;
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function demo(instance) {
    await sleep(2000);
    instance.next();
}

$.fn.extend({
    animateCss: function (animationName, callback) {
        var animationEnd = (function (el) {
            var animations = {
                animation: 'animationend',
                OAnimation: 'oAnimationEnd',
                MozAnimation: 'mozAnimationEnd',
                WebkitAnimation: 'webkitAnimationEnd',
            };

            for (var t in animations) {
                if (el.style[t] !== undefined) {
                    return animations[t];
                }
            }
        })(document.createElement('div'));

        this.addClass('animated ' + animationName).one(animationEnd, function () {
            $(this).removeClass('animated ' + animationName);

            if (typeof callback === 'function') callback();
        });

        return this;
    },
});

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

let indeterminate_checkbox = () => {
    let indeterminateCheckbox = document.querySelectorAll(".indeterminate-checkbox");

    indeterminateCheckbox.forEach(function (element) {
        if (element !== null) element.indeterminate = true;
    });
}
