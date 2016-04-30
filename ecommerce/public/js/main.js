$(function () {
    $(".calendario").datepicker();
});

$(".calendario").mask("99-99-9999");


$(document).ready(function () {
    $(':input:enabled:visible:first').focus();
});


$(function () {
    $(":input:text").tooltip({
        position: {
            my: "right top-30",
            at: "left-2 bottom+0",
            using: function (position, feedback) {
                $(this).css(position);
                $("<div>")
                        .addClass("arrow")
                        .addClass(feedback.vertical)
                        .addClass(feedback.horizontal)
                        .appendTo(this);
            }
        }
    });
});

function ajaxGet(url, element) {

    $.ajax({
        type: "GET",
        url: url,
        data: "",
        beforeSend: function () {
        },
        success: function (txt) {

            $("#" + element).html(txt);
        },
        error: function (txt) {
        }
    });

}

function limparBusca() {
    document.getElementById('busca').value = '';
    document.FormBuscar.submit();
}

function mainmenu() {
    $(" #nav ul ").css({
        display: "none"
    }); // Opera Fix
    $(" #nav li").hover(function () {
        $(this).find('ul:first').css({
            visibility: "visible",
            display: "none"
        }).show(400);
    }, function () {
        $(this).find('ul:first').css({
            visibility: "hidden"
        });
    });
}

$(document).ready(function () {
    mainmenu();
});


//COLOCA OS ID'S EM TODOS OS OPTIONS PARA PODER MANIPULAR AS INFORMACOES
function setID(tableI, tableII) {

    var optsI = document.getElementById(tableI).options;
    for (var i = 0, j = optsI.length; i < j; i++) {
        optsI[i].setAttribute('id', optsI[i].value);
    }

    var optsII = document.getElementById(tableII).options;
    for (var i = 0, j = optsII.length; i < j; i++) {
        optsII[i].setAttribute('id', optsII[i].value);
    }
}

//Delete um Registro de uma Tabela e Insere em Outra
function deleteRegistro(elementDel, elementAdd, BASE_URL) {

    var idPrograma;
    var json;
    var idPerfil;

    if (document.getElementById('perfil')) {
        idPerfil = document.getElementById('perfil').value;
    }

    idProgDel = document.getElementById(elementDel);
    descProgDel = document.getElementById(idProgDel.value);
    // Elemento pra Adicionar, ID e Valor do Option
    adicionaRegistro(elementAdd, idProgDel.value, descProgDel.innerHTML);

    idPrograma = idProgDel.value;

    if (document.getElementById('perfil')) {
        json = '{"dados": {"indices":[' + idPrograma + '], "id_perfil":' + idPerfil + '}}';

        saveAjax(BASE_URL + '/auth/programas-perfis/save', json);
        saveAjax(BASE_URL + '/auth/programas-perfis/delete', json);
    }

    //Remove o Registro da tabela em que esta
    descProgDel.parentNode.removeChild(descProgDel);
}

function adicionaRegistro(elementNew, id, desc) {

    elemento = document.getElementById(elementNew);
    elemento.innerHTML += "<option id='" + id + "' value='" + id + "'>" + desc + "</option>";

}
//Verifica s e Houve Confirmação ou não para mudar as permissões
function confirmaRemocao() {
    var sim;

    if (document.getElementById("tableII").options.length > 0) {
        sim = confirm('Você realmente deseja excluir esta permissão?');
        if (!sim) {
            exit;
        }
    }
}

function AdicionaTodos(BASE_URL) {
    var indices = new Array();
    var json;
    var idperfil;

    for (var i = 0; i < document.getElementById("tableI").options.length; i++)

    {
        idProgDel = document.getElementById("tableI").options[i];
        descProgDel = document.getElementById("tableI").options[i].innerHTML;

        adicionaRegistro("tableII", idProgDel.value, descProgDel);
        indices[i] = idProgDel.value;
    }

    if (document.getElementById('perfil')) {
        idperfil = document.getElementById('perfil').value;

        json = '{"dados":{"indices":[' + indices + '], "id_perfil":' + idperfil + '}}';

        saveAjax(BASE_URL + '/auth/programas-perfis/save', json);
    }

    var i = 0;
    while (i < document.getElementById("tableI").options.length) {
        document.getElementById("tableI").remove(i);
    }


    idProgDel = document.getElementById("tableI").options[i];
    descProgDel = document.getElementById("tableI").options[i].innerHTML;

    adicionaRegistro("tableII", idProgDel.value, descProgDel);

    var i = 0;
    while (i < document.getElementById("tableI").options.length) {
        document.getElementById("tableI").remove(i);
    }

}

function RemoveTodos(BASE_URL) {

    var indices = new Array();
    var json;
    var idperfil;

    for (var i = 0; i < document.getElementById("tableII").options.length; i++)
    {
        idProgDel = document.getElementById("tableII").options[i];
        descProgDel = document.getElementById("tableII").options[i].innerHTML;

        adicionaRegistro("tableI", idProgDel.value, descProgDel);
        indices[i] = idProgDel.value;
    }

    if (document.getElementById('perfil')) {
        idperfil = document.getElementById('perfil').value;

        json = '{"dados":{"indices":[' + indices + '], "id_perfil":' + idperfil + '}}';

        saveAjax(BASE_URL + '/auth/programas-perfis/delete', json);
    }

    var i = 0;
    while (i < document.getElementById("tableII").options.length) {
        document.getElementById("tableII").remove(i);
    }

    adicionaRegistro("tableI", idProgDel.value, descProgDel);

    var i = 0;
    while (i < document.getElementById("tableII").options.length) {
        document.getElementById("tableII").remove(i);
    }

}

//SELECIONA TODOS OS DADOS DA TABELA I E TABELA ii E MANDA PARA O INDEX
function submitForm()
{
    for (var i = 0; i < document.getElementById("tableI").options.length; i++)
    {
        document.getElementById("tableI").options[i].selected = true;
    }
    for (var i = 0; i < document.getElementById("tableII").options.length; i++)
    {
        document.getElementById("tableII").options[i].selected = true;
    }
    document.btn = true

    document.Tables.submit();

}


//Função com JQuery para manipular Ajax e Json dentro de Programa Perfil
function saveAjax(url, json) {
    $.ajax({
        type: "POST",
        url: url,
        data: json
    }).done(function (msg) {
        //alert( "Data Saved: " + msg );
    });
}



function showHelp() {
    $(function () {
        $(".help-system-text").dialog({
            modal: true
        });
    });

}


function bookmarked(url, title) {

    if (window.sidebar)
        window.sidebar.addPanel(title, url, "");
    else if (window.opera && window.print) {

        var mbm = document.createElement('a');
        mbm.setAttribute('rel', 'sidebar');
        mbm.setAttribute('href', url);
        mbm.setAttribute('title', title);
        mbm.click();
    } else if (document.all) {

        window.external.AddFavorite(url, title);

    } else {
        alert('Pressione as teclas CTRL + D para adicionar aos favoritos.');
    }

}


function verificaIguais(campo1, campo2, element, msg, msgSure) {

    value1 = document.getElementById(campo1).value;
    value2 = document.getElementById(campo2).value;

    elementLoading = document.getElementById(element);
    if (value1 != '' && value2 != '') {
        if (value1 != value2) {
            elementLoading.innerHTML = msg;
        } else {

            elementLoading.innerHTML = msgSure;
        }
    }
}


jQuery(function ($) {
    $.datepicker.regional['pt-BR'] = {
        closeText: 'Fechar',
        prevText: '&#x3c;Anterior',
        nextText: 'Pr&oacute;ximo&#x3e;',
        currentText: 'Hoje',
        monthNames: ['Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
            'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        dayNames: ['Domingo', 'Segunda-feira', 'Ter&ccedil;a-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        weekHeader: 'Sm',
        dateFormat: 'dd-mm-yy',
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''};
    $.datepicker.setDefaults($.datepicker.regional['pt-BR']);
});