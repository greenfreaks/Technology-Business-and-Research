jQuery(document).ready(function ($) {

    $('#pestanas').tabs();


    $("#siguiente-btn").on("click", function () {
        /*Function*/
        console.log("click");
        document.getElementById("progress").style.width = "50%";
        $("#tab-arbol").removeClass("disabled");
        $('#pestanas').tabs('select', 'tab-arbol');

    });



});
