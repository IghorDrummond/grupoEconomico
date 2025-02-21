//Bibliotecas
import { ToastActive, ToastOff, Loading } from './lib/script.js';
/*
===========================================
FONTE: login.js
TIPO: Modulo
DESCRIÇÃO: Realiza operações entre Client e Server para edição, deleção e atualização de dados
PROGRAMADOR(A): Ighor Drummond
DATA: 15/02/2025
===========================================
@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
*/

//Carrega os eventos 
$(document).ready(() => {
    //Declaração de variaveis
    //Objeto - o
    var oCheckList = "";
    var lastChecked = null;
    /*
    ===========================================
    EVENTO: .formulario
    TIPO: submit
    DESCRIÇÃO: Envia dados do formulário para o servidor
    PROGRAMADOR(A): Ighor Drummond
    DATA: 17/02/2025
    ===========================================
    @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
    */
    $(document).on("click", '.logoutoff', (e) => {
        //Liga tela de carregamento
        Loading(true);
    });

    /*
    ===========================================
    EVENTO: .editar
    TIPO: click
    DESCRIÇÃO: Edita dado de acordo com oque está selecionado por ultimo
    PROGRAMADOR(A): Ighor Drummond
    DATA: 18/02/2025
    ===========================================
    @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
    */
    $(document).on("click", '.editar', () => {
        //Verifica se tem algum registro marcado 
        if (oCheckList) {
            CarregaFormulario($('.secao_estilizada').attr('data-link'), 2); //Carrega formulário na pagina
        } else {
            ToastActive(2, "Para editar é necessário selecionar um registro.", "Selecione um registro");
        }
    });

    /*
    ===========================================
    EVENTO: .adicionar
    TIPO: click
    DESCRIÇÃO: Adiciona dado de acordo com oque está selecionado por ultimo
    PROGRAMADOR(A): Ighor Drummond
    DATA: 18/02/2025
    ===========================================
    @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
    */
    $(document).on("click", '.adicionar', () => {
        CarregaFormulario($('.secao_estilizada').attr('data-link'), 1); //Carrega formulário na pagina
    });

    /*
    ===========================================
    EVENTO: .deletar
    TIPO: click
    DESCRIÇÃO: Deleta dado de acordo com oque está selecionado por ultimo
    PROGRAMADOR(A): Ighor Drummond
    DATA: 18/02/2025
    ===========================================
    @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
    */
    $(document).on("click", '.deletar', () => {
        //Verifica se tem algum registro marcado 
        if (oCheckList) {

            if (confirm('Você tem certeza que quer deletar este registro? após confirmado não será possível ser recuperado.')) {
                //Deleta o registro
                $.ajax({
                    url: "/delete-" + $('.secao_estilizada').attr('data-link'),
                    method: "DELETE",
                    data: oCheckList,
                    beforeSend: function () {
                        Loading(true);
                    },
                    complete: function () {
                        Loading(false);
                    },
                    success: function (response) {
                        //Valida se ocorreu tudo bem com a operação
                        if (response.error) {
                            ToastActive(1, response.message, response.title);
                        } else {
                            setTimeout(() => location.reload(), 1000);
                            ToastActive(0, response.message, response.title);
                        }
                    },
                    error(jqXHR, textStatus, errorThrown) {
                        //Fecha formulário
                        $('dialog').remove();
                        ToastActive(1, "Não foi possível carregar a página", textStatus);
                    }
                });
            }

        } else {
            ToastActive(2, "Para Deletar é necessário selecionar um registro.", "Selecione um registro");
        }
    });

    /*
    ===========================================
    EVENTO: .registros
    TIPO: submit
    DESCRIÇÃO: Envia dados do registro ao servidor
    PROGRAMADOR(A): Ighor Drummond
    DATA: 18/02/2025
    ===========================================
    @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
    */
    $(document).on("submit", '.registro', (e) => {
        //Evita carregamento da página
        e.preventDefault();

        // Serializa os dados do formulário
        let formData = $('.registro').serializeArray();

        // Adiciona o ID do checkbox selecionado ao objeto de dados
        if (oCheckList && oCheckList.id) {
            formData.push({ name: "id", value: oCheckList.id });
        }

        CarregaDados($('.registro').attr('action'), $('.registro').attr('method'), formData);
    });

    /*
    ===========================================
    EVENTO: .close_registro
    TIPO: click
    DESCRIÇÃO: Fecha formulário
    PROGRAMADOR(A): Ighor Drummond
    DATA: 18/02/2025
    ===========================================
    @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
    */
    $(document).on("click", '.close_registro', (e) => {
        //Fecha formulário
        $('dialog').remove();
    });

    /*
    ===========================================
    EVENTO: .form-check-input
    TIPO: click
    DESCRIÇÃO: Seleciona ou deseleciona um item da lista
    PROGRAMADOR(A): Ighor Drummond
    DATA: 18/02/2025
    ===========================================
    @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
    */
    $(document).on("click", '.form-check-input', (e) => {
        // Armazena o item clicado
        let currentItem = $(e.target);

        // Verifica se o checkbox foi marcado
        if (currentItem.prop('checked')) {
            // Verifica se há outro checkbox marcado
            if (lastChecked && lastChecked !== currentItem) {
                lastChecked.prop('checked', false); // Desmarcar o checkbox anterior
            }

            // Atualiza o último item marcado
            lastChecked = currentItem;

            // Armazena os dados no objeto global
            oCheckList = {
                id: currentItem.attr('id'), // ID do grupo (se aplicável)
                nome: currentItem.attr('value'), // Nome do grupo
                _token: $('input[name="_token"]').val() // Token CSRF
            };
        } else {
            // Caso o checkbox seja desmarcado, limpa os dados
            lastChecked = null;
            oCheckList = "";
        }
    });
})

//=======================================Funções

/*
=============================================
FUNÇÃO: CarregaDados()
PARÂMETROS: cRota (string) - rota de requisição / type (string) - tipo da requisição / dados (object) dados a ser enviado na requisição
DESCRIÇÃO: Envia dados para o server
RETORNO: Não Há
PROGRAMADOR(A): Ighor Drummond
DATA: 18/02/2025
=============================================
@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
*/
function CarregaDados(cRota = "", type = "GET", formData = []) {
    //Prepara Ajax
    $.ajax({
        url: cRota,
        method: type,
        data: $.param(formData),
        beforeSend: function () {
            Loading(true);
        },
        complete: function () {
            Loading(false);
        },
        success: function (response) {
            //Valida se ocorreu tudo bem com a operação
            if (response.error) {
                ToastActive(1, response.message, response.title);

                //Caso tiver campos incorretos retornado pela Requisição, ele assina-la para o Client
                if (response.data) {
                    //Percorre campos incorretos
                    response.data.forEach((campo) => {
                        if (campo) $('input[name="' + campo + '"]').addClass("border border-danger");
                    });
                }
            } else {
                //Fecha formulário
                $('dialog').remove();
                setTimeout(() => location.reload(), 1000);
                ToastActive(0, response.message, response.title);
            }

        },
        error(jqXHR, textStatus, errorThrown) {
            //Fecha formulário
            $('dialog').remove();
            ToastActive(1, "Não foi possível carregar a página", textStatus);
        }
    });
}

/*
=============================================
FUNÇÃO: CarregaFormulario()
PARÂMETROS: rotas (string) - Rota para recuperar HTML do formulário / $nOpc (int) -  operação que o formulário vai realizar
DESCRIÇÃO: Carrega Formulário HTML em rotas definidas
RETORNO: Não Há
PROGRAMADOR(A): Ighor Drummond
DATA: 18/02/2025
=============================================
@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
*/
function CarregaFormulario(cRota = "", nOpc = 0) {
    //Prepara Ajax
    $.ajax({
        url: "/formulario-" + cRota,
        method: "GET",
        beforeSend: function () {
            Loading(true);
        },
        complete: function () {
            Loading(false);
        },
        success: function (response) {
            let cOpc = "";
            let cType = "GET";

            //Carrega Formulário no topo do elemento
            $('.secao_estilizada').prepend(response);

            //Seleciona rota do action
            switch (nOpc) {
                case 1:
                    cOpc = "/create-" + cRota;
                    cType = "POST"
                    break;
                case 2:
                    cOpc = "/update-" + cRota;
                    cType = "PUT"
                    break;
            }

            //Atualiza dado do formulário
            $('.registro').attr('action', cOpc);
            $('.registro').attr('method', cType);
        },
        error(jqXHR, textStatus, errorThrown) {
            ToastActive(1, textStatus, "Não foi possível carregar a página");
        }
    });
}
