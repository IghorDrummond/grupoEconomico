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
	EVENTO: .formulario
	TIPO: submit
	DESCRIÇÃO: Envia dados do formulário para o servidor
	PROGRAMADOR(A): Ighor Drummond
	DATA: 17/02/2025
	===========================================
	@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
	*/
	$(document).on("submit", '.formulario', (e) => {
		e.preventDefault();//Evita carregar a página

		//Envia dados para o servidor
		EnviarDados();
	});

	/*
	===========================================
	EVENTO: .cadastrar
	TIPO: click
	DESCRIÇÃO: Carrega formulário de cadastro
	PROGRAMADOR(A): Ighor Drummond
	DATA: 15/02/2025
	===========================================
	@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
	*/
	$(document).on("click", '.cadastrar', () => {
		//Animação do desligamento do formulário
		$(".formulario").fadeOut();

		//Carrega Página de Login no formulário
		CarregaPaginas('/cadastrar');
	});

	/*
	===========================================
	EVENTO: .login
	TIPO: click
	DESCRIÇÃO: Carrega formulário de login
	PROGRAMADOR(A): Ighor Drummond
	DATA: 15/02/2025
	===========================================
	@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
	*/
	$(document).on("click", ".login", () => {
		//Animação do desligamento do formulário
		$(".formulario").fadeOut();

		//Carrega Página de Login no formulário
		CarregaPaginas('/login');
	});

	/*
	===========================================
	EVENTO: .esqueci-senha
	TIPO: click
	DESCRIÇÃO: Carrega formulário de esqueci a senha
	PROGRAMADOR(A): Ighor Drummond
	DATA: 15/02/2025
	===========================================
	@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
	*/
	$(document).on("click", ".esqueci-senha", () => {
		//Animação do desligamento do formulário
		$(".formulario").fadeOut();

		//Carrega Página de Login no formulário
		CarregaPaginas('/esqueci-senha');
	});

	/*
	===========================================
	EVENTO: .email
	TIPO: click
	DESCRIÇÃO: Carrega formulário de esqueci a senha
	PROGRAMADOR(A): Ighor Drummond
	DATA: 15/02/2025
	===========================================
	@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
	*/
	$(document).on("click", ".email", () => {
		//Animação do desligamento do formulário
		$(".formulario").fadeOut();

		//Carrega Página de Login no formulário
		CarregaPaginas('/gera-codigo');
	});

})

//=======================================Funções

/*
=============================================
FUNÇÃO: CarregaPaginas()
PARÂMETROS: rotas (string) - Rota para recuperar HTML do formulário
DESCRIÇÃO: Carrega HTML em rotas definidas
RETORNO: Não Há
PROGRAMADOR(A): Ighor Drummond
DATA: 15/02/2025
=============================================
@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
*/
function CarregaPaginas(rota = "") {
	//Prepara Ajax
	$.ajax({
		url: "/pagina" + rota,
		method: "GET",
		timeout: 15000,
		success: function (response) {
			$('.formulario').html(response);//Sobrescreve formulário
			$('.formulario').attr("action", "/pagina" + rota);//Atualiza rota do formulário 

			//Remove todos as bordas dos inputs
			$('.formulario').find('input').removeClass("border border-danger");

			//Ativa o formulário com novos campos carregados
			$('.formulario').fadeIn();
		},
		error(jqXHR, textStatus, errorThrown) {
			ToastActive(1, textStatus, "Não foi possível carregar a página");
		}
	});
}

/*
=============================================
FUNÇÃO: EnviarDados()
PARÂMETROS: Não há 
DESCRIÇÃO: Envia dados do formulário para o servidor
RETORNO: Não Há
PROGRAMADOR(A): Ighor Drummond
DATA: 17/02/2025
=============================================
@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
*/
function EnviarDados() {
	//Remove todos as bordas dos inputs
	$('.formulario').find('input').removeClass("border border-danger");

	//Prepara Ajax
	$.ajax({
		url: $(".formulario").attr("action"),
		method: "POST",
		data: $('.formulario').serialize(),
		dataType: "json",
		beforeSend: function () {
			Loading(true);
		},
		complete: function () {
			Loading(false);
		},
		success: function (response) {
			//Valida se ocorreu tudo bem
			if(response.error){
				//Retorna uma mensagem para o usuário
				ToastActive(2, response.message, response.title);

				//Caso tiver campos incorretos retornado pela Requisição, ele assina-la para o Client
				if(response.data){
					//Percorre campos incorretos
					response.data.forEach((campo) =>{
						if(campo) $('input[name="'+ campo +'"]').addClass("border border-danger");
					});
				}
			}else{
				//Mostra toast de sucesso!
				ToastActive(0, response.message, response.title);

				//Caso for login aceito, redireciona para tela home
				if($(".formulario").attr("action") === '/pagina/login'){
					window.location.href = "/home";
				}

				//Redireciona para a página desejada
				if(response.redirect){
					$('.formulario').fadeOut();
					
					CarregaPaginas(response.redirect);
				}
			}
		},
		error(jqXHR, textStatus, errorThrown) {
			ToastActive(1, "Não foi possível carregar a página", textStatus);
		}
	});
}