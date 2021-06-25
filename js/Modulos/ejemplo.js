let s;

let questionaire = {
    name: "ejemplo",
    diagrama: 'ejemplo.svg',
    qs: [
        {
            id: 1,
            status: false,
            is_active: true,
            svg_paths_a: [
                "#path335",
                "#polygon333",
                "#path139",
                "#path177",
                "#polygon175",

            ],
            svg_paths_n: [
                "path335",
                "#polygon333",
                "#path163",
                "#path349",
                "#polygon347",

            ],
            div: "Cuentas con la infraestructura para explotar tu tecnología?",
            is_end: false,
            next_q_a: 2,
            next_q_n: 3,
        },
        {
            id: 2,
            status: false,
            is_active: false,
            svg_paths_a: [
                "#path91",
                "#path209",
                "#polygon207",

            ],
            svg_paths_n: [
                "#path79",
                "#path581",
                "#polygon579",

            ],
            div: "Cuentas con el personal necesario?",
            is_end: false,
            next_q_a: 4,
            next_q_n: 5,
        },
        {
            id: 3,
            status: false,
            is_active: false,
            svg_paths_a: [
                "#path103",
                "#path177",
                "#polygon175",

            ],
            svg_paths_n: [
                "#path127",
                "#rect439",
                "#path115",
                "#circle477",

            ],
            div: "Puedes conseguir la infraestructura para explotar esa tecnología?",
            is_end: false,
            next_q_a: 2,
            next_q_n: 6,
        },
        {
            id: 4,
            status: false,
            is_active: false,
            svg_paths_a: [
                "#path43",
                "#circle241",

            ],
            svg_paths_n: [
                "#path31",
                "#path533",
                "#polygon531",

            ],
            div: "Puedes crear un Startup para explotar la tecnologia?",
            is_end: false,
            next_q_a: 7,
            next_q_n: 8,
        },
        {
            id: 5,
            status: false,
            is_active: false,
            svg_paths_a: [
                "#path139",
                "#path177",
                "#polygon175",

            ],
            svg_paths_n: [
                "#path163",
                "#path349",
                "#polygon347",

            ],
            div: "Es factible conseguir personal?",
            is_end: false,
            next_q_a: 4,
            next_q_n: 6,
        },
        {
            id: 6,
            status: false,
            is_active: false,
            svg_paths_a: [
                "#path139",
                "#path177",
                "#polygon175",

            ],
            svg_paths_n: [
                "#path163",
                "#path349",
                "#polygon347",

            ],
            div: "Busca interesados en comprar o licenciar la tecnología",
            is_end: true,
            next_q_a: null,
            next_q_n: null,
        },
        {
            id: 7,
            status: false,
            is_active: false,
            div: "Crea un Starup",
            is_end: true,
        },
        {
            id: 8,
            status: false,
            is_active: false,
            svg_paths_a: [
                "#path139",
                "#path177",
                "#polygon175",

            ],
            svg_paths_n: [
                "#path163",
                "#path349",
                "#polygon347",

            ],
            div: "Hay interesados en comprar o licenciar la tecnología?",
            is_end: false,
            next_q_a: 9,
            next_q_n: 10,
        },
        {
            id: 9,
            status: false,
            is_active: false,
            div: "Transferir",
            is_end: true,
        },
        {
            id: 10,
            status: false,
            is_active: false,
            div: "Hacer marketing Tecnológico",
            is_end: true,
        },

//        {
//            id: 0,
//            status: false,
//            svg_paths: [
//                "#",
//            ],
//            div: "div content",
//            is_end: true,
//            next_q_a: null,
//            next_q_n: null,
//        },
    ],
    php: null
};


jQuery(document).ready(function ($) {

    load_questionaire(questionaire, "#questionaire_container");

    render_questionaire(questionaire);

    $(".btn_r").on("click", function () {
        /*Function*/
        let [ans, q_id] = $(this).attr('id').split("_");

        var next_q;
        if (ans == 'a') {

            next_q = questionaire.qs.find(x => x.id == q_id).next_q_a;
            questionaire.qs.find(x => x.id == q_id).status = true;
            console.log("afirmativo " + next_q);
            render_questionaire(questionaire);

        } else {
            next_q = questionaire.qs.find(x => x.id == q_id).next_q_n;
            questionaire.qs.find(x => x.id == q_id).status = false;
            console.log("negativo " + next_q);
            render_questionaire(questionaire);
        }

        questionaire.qs.find(x => x.id == next_q).is_active = true;

    });

    $(".restart").on("click", function () {
        for (let question of questionaire.qs) {
            question.status = false;
        }
        questionaire.qs.find(x => x.id == 1).is_active = true;

        render_questionaire(questionaire);
    });




});

function load_questionaire(questions, questionaire_container) {

    let container = $(questionaire_container);

    container.append('<div class="row"><div class="col s12 diagrama grey lighten-4"><svg id="svg_diagram"></svg></div></div>');

    s = Snap("#svg_diagram");

    Snap.load(questions.diagrama, function (f) {
        s.append(f);
    });

    setTimeout(function () {
        s.attr({
            viewBox: s.getBBox().x + ' ' + s.getBBox().y + ' ' + s.getBBox().width + ' ' + s.getBBox().height
        });
    }, 10);

    for (let question of questions.qs) {

        let butons;
        if (question.is_end) {
            buttons = '<div class="row"><div class="col s12 center"><a class="waves-effect waves-light btn restart green"><i class="material-icons right">done</i>Reiniciar</a></div></div>';
        } else {
            buttons = '<div class="row"><div class="col s6 center"><a class="waves-effect waves-light btn btn_r red" id="n_' + question.id + '"><i class="material-icons right">close</i>No</a></div><div class="col s6 center"><a class="waves-effect waves-light btn btn_r green" id="a_' + question.id + '"><i class="material-icons right">done</i>Si</a></div></div>';
        }

        let div = '<div class="row question" id="q' + question.id + '"><div class="col s12 center"><h4>' + question.div + '</h4></div>' + buttons + '</div>';

        container.append(div);
    }

}

function render_questionaire(questions) {

    $(".question").hide();

    for (let question of questions.qs) {

        if (question.is_active) {
            $("#q" + question.id).show("slow");
        }
        //
        //        for (let path of question.status ? question.svg_paths_a : question.svg_paths_n) {
        //            setTimeout(function () {
        //        s.select(path).attr({
        //            stroke: "#0000ff"
        //        });
        //    }, 10);
        //        }
    }
}
