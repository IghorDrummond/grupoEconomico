//Bibliotecas
import { ToastActive, ToastOff, Loading } from './lib/script.js';

/*
===========================================
FONTE: login.js
TIPO: Modulo
DESCRIÇÃO: Realiza operações entre Client e Server para login, cadastro ou esqueci a senha
PROGRAMADOR(A): Ighor Drummond
DATA: 15/02/2025
===========================================
@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
*/

//Carrega os eventos 
$(document).ready(() => {
    /*
    ===========================================
    EVENTO: .relatorios
    TIPO: click
    DESCRIÇÃO: Extraí Excel
    PROGRAMADOR(A): Ighor Drummond
    DATA: 20/02/2025
    ===========================================
    @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
    */
    $(document).on("submit", '.relatorios', (e) => {
        //Impede de carregadar a página
        e.preventDefault();

        //Deleta o registro
        $.ajax({
            url: '/relatorio-extrair',
            method: "GET",
            data: $('.relatorios').serialize(),
            dataType: 'json',
            beforeSend: function () {
                Loading(true);
            },
            complete: function () {
                Loading(false);
            },
            success: function (response) {
                window.open(response.data.file, '_blank'); 
            },
            error(jqXHR, textStatus, errorThrown) {
                //Fecha formulário
                $('dialog').remove();
                ToastActive(1, "Não foi possível gerar o excel.", textStatus);
            }
        });
    });
});

