/*
===========================================
FONTE: Script.js
TIPO: Biblioteca
DESCRIÇÃO: Aglomerado de funções para o Front End
PROGRAMADOR(A): Ighor Drummond
DATA: 15/02/2025
===========================================
@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
*/

//Declaração de variaveis
//Elementos
const Toast = new bootstrap.Toast( $('#liveToast') ); 
//String - c

/*
===========================================
FUNÇÃO: ToastActive()
PUBLICA: Sim
PARÂMETROS: type (int) - Responsável por dizer o nível da mensagem / message (string) - mensagem do Toast / title (string) - Título da mensagem
DESCRIÇÃO: Responsável por ativar o Toast do Bootstrap de mensagem no site.
PROGRAMADOR(A): Ighor Drummond
DATA: 15/02/2025
===========================================
@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
*/
export function ToastActive(type = 0, message = "", title = ""){
	//Declaração de variaveis
	//String - c
	let cLevel = "";
	let cColor = "";

	//Valida qual é o nível da mensagem
	switch(type){
		case 0:
			cLevel = "SUCESSO";
			cColor = "green";
			break;
		case 1: 
			cLevel = "ERROR";
			cColor = "red";
			break;
		case 2:
			cLevel = "ATENÇÃO";
			cColor = "orange";
			break;
	}

	//Ativa Toast com o tipo e mensagem desejada
	$('.title_toast').text( cLevel + ': ' + title);//Ejeta título
	$('.toast-body').text(message);//Ejeta mensagem
	$('.toast-header').css({"background-color":  cColor});//Ejeta cor do nível

	//Liga Toast
	Toast.show();

	//Programa para desligar automáticamente
	setTimeout(() => ToastOff(), 5000);
}

/*
===========================================
FUNÇÃO: ToastOff()
PUBLICA: Sim
PARÂMETROS: type (int) - Responsável por dizer o nível da mensagem / message (string) - mensagem do Toast
DESCRIÇÃO: Responsável por desligar o Toast do Bootstrap de mensagem no site.
PROGRAMADOR(A): Ighor Drummond
DATA: 15/02/2025
===========================================
@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
*/
export function ToastOff(){
	//Valida se Toast existe no site
	if(Toast){
        Toast.hide(); // Esconde o Toast
    }
}

/*
===========================================
FUNÇÃO: Loading()
PUBLICA: Sim
PARÂMETROS: state (bool) - Estado de visualização do Loading
DESCRIÇÃO: Responsável por ativar/desativar tela de carregamento
PROGRAMADOR(A): Ighor Drummond
DATA: 15/02/2025
===========================================
@ DATA - DESCRIÇÃO - PROGRAMADOR(A)
*/
export function Loading(state = false){
	//Liga ou desliga tela de carregamento

	if(state){
		$('.loading').css({"display": "flex"});
		$(".loading").fadeIn();
	}else{
		$(".loading").fadeOut();
	}
}
