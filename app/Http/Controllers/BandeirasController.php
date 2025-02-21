<?php


/*
==================================================
Namespace: App\Http\Controllers
Descrição: Responsável por agrupar operações relacionadas Bandeiras
Programador(a): Ighor Drummond
Data: 18/02/2025
==================================================
Modificações:
@ Data - Descrição - Programador(a)
*/
namespace App\Http\Controllers {
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Pagination\Paginator;
    use App\Models\Bandeira;
    use App\Models\grupoEconomico;
    use App\Traits\JsonFormatted;
    use App\Traits\SecurityDados;
    use App\Traits\EditDatas;
    use App\Traits\Requests;

    /*
    ==================================================
    Classe: BandeirasController
    Extend: Controller
    Descrição: Responsável por realizar operações relacionadas a bandeiras
    Programador(a): Ighor Drummond
    Data: 18/02/2025
    ==================================================
    Modificações:
    @ Data - Descrição - Programador(a)
    */
    class BandeirasController extends Controller
    {
        //Bibliotecas 
        use SecurityDados;
        use JsonFormatted;
        use EditDatas;

        /*  
        ===============================================
        MÉTODO: Store()
        DESCRIÇÃO: Realiza listagem dos dados na página principal
        PARÂMETROS: Não há 
        RETORNO: Template - view
        PROGRAMADOR(A): Ighor Drummond
        DATA: 18/05/2022
        ===============================================
        @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
        */
        public function Store()
        {
            //Declaração de variaveis
            //Array - a
            $aListFlags = [];

            //Redireciona para a tela de login
            if (!Auth::check()) return redirect()->route('/login');

            // Ativa a paginação com Bootstrap 5
            Paginator::useBootstrapFive();

            //Realiza pesquisa de bandeiras por paginação
            $aListFlags = Bandeira::select('bandeiras.nome', 'groupE.nome as grupo_nome', 'bandeiras.id')
                ->join('grupo_economicos as groupE', 'groupE.id', '=', 'bandeiras.grupo_economico')
                    ->where('bandeiras.active', true)
                        ->where('groupE.active', true)
                            ->orderBy('bandeiras.id', 'DESC')
                                ->paginate(10);

            //Realiza criptografia dos IDs das bandeiras
            $aListFlags->getCollection()->transform(function ($group) {
                $group->id_criptografado = $this->Criptografia($group->id);
                return $group;
            });

            //Retorna View com os itens selecionados
            return view('bandeiras.index', compact('aListFlags'));
        }

        /*  
        ===============================================
        MÉTODO: Create()
        DESCRIÇÃO: Cria novo dado no banco de dados
        PARÂMETROS: $request (Request) - dados da requisição
        RETORNO: JSON - status da operação
        PROGRAMADOR(A): Ighor Drummond
        DATA: 18/05/2022
        ===============================================
        @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
        */
        public function Create(Request $request)
        {
            //Declaração de variaveis
            //Array - a
            $aCamposObrigatorios = ['_token', 'nome', 'grupo'];
            $aData = [];
            $aDados = [];

            //Redireciona para a tela de login
            if (!Auth::check()) return redirect()->route('/login');

            //Recupera dados enviado pelo formulário
            $aDados = $request->all();

            //Valida campos obrigatórios
            foreach ($aCamposObrigatorios as $Campo) {
                //Valida se o campo existe
                if (!isset($aDados[$Campo]) || empty($aDados[$Campo]))
                    return response()->json($this->toJsonFormatted(true, "Campos Obrigatórios.", "O campo $Campo é obrigatório", [$Campo]));

                //Sanatiza Dados
                $aDados[$Campo] = $this->SanatizaDados($aDados[$Campo]);
            }

            //Descriptografar ID do grupo
            $aDados['grupo'] = $this->Descriptografia($aDados['grupo']);

            //Valida se existe o grupo informado
            $aResultado = grupoEconomico::where([
                ['id', '=', (int)$aDados['grupo']],
                ['active', '=', 1],
            ])->first();

            if (!$aResultado)
                return response()->json($this->toJsonFormatted(true, "Grupo não existe!", "O Grupo informado não está cadastrado no sistema", ['grupo']));

            //Valida se já existe a bandeira criada
            $aResultado = bandeira::where([
                ['nome', '=', $aDados['nome']],
                ['active', '=', 1],
            ])->first();

            if ($aResultado)
                return response()->json($this->toJsonFormatted(true, "Bandeira já cadastrada.", "A Bandeira {$aDados['nome']} já está cadastrada em nosso sistema.", $aCamposObrigatorios));

            //Prepara dados para salvar
            $aData = [
                'nome' => $aDados['nome'],
                'grupo_economico' => $aDados['grupo']
            ];

            //Insere dados na tabela
            if (!$this->Insert('bandeiras', $aData))
                return response()->json($this->toJsonFormatted(true, "Não foi possível salvar", "Não foi possível salvar o dado no banco de dados. tente novamente ou mais tarde.", $aCamposObrigatorios));

            //Registra ato realizado no sistema
            $this->RegistroMudanca("INSERÇÃO", [], $aData, "bandeiras");

            //Retorna View com os itens selecionados
            return response()->json($this->toJsonFormatted(false, "Bandeira Criada!", "Adicionado a bandeira {$aDados['nome']} no sistema."));
        }

        /*  
        ===============================================
        MÉTODO: Update()
        DESCRIÇÃO: Atualiza dado no banco de dados
        PARÂMETROS: $request (Request) - dados da requisição
        RETORNO: JSON - status da operação
        PROGRAMADOR(A): Ighor Drummond
        DATA: 18/05/2022
        ===============================================
        @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
        */
        public function Update(Request $request)
        {
            //Declaração de variaveis
            //Array - a
            $aCamposObrigatorios = ['_token', 'id'];
            $aData = [];
            $aDados = [];

            //Redireciona para a tela de login
            if (!Auth::check()) return redirect()->route('/login');

            //Recupera dados enviado pelo formulário
            $aDados = $request->all();

            //Valida campos obrigatórios
            foreach ($aCamposObrigatorios as $Campo) {
                //Valida se o campo existe
                if (!isset($aDados[$Campo]) || empty($aDados[$Campo]))
                    return response()->json($this->toJsonFormatted(true, "Campos Obrigatórios.", "O campo $Campo é obrigatório", [$Campo]));

                //Sanatiza Dados
                $aDados[$Campo] = $this->SanatizaDados($aDados[$Campo]);
            }

            //Valida se foi passado grupo 
            if(isset($aDados["grupo"]) and !empty($aDados["grupo"])){
                //Descriptografar
                $aDados['grupo'] = $this->Descriptografia($aDados['grupo']);

                //Valida se já existe um grupo com este nome
                $aResultado = grupoEconomico::where([
                    ['id', '=', (int)$aDados['grupo']],
                    ['active', '=', 1],
                ])->first();
                
                //Retorna inconsistência
                if (!$aResultado) return response()->json($this->toJsonFormatted(true, "Grupo não existente!", "O Grupo informado não existe no sistema.", ['grupo']));
            }
            
            if(isset($aDados["nome"]) and !empty($aDados["nome"])){
                //Valida se existe já existe uma bandeira com o nome alterado informado
                $aResultado = Bandeira::where([
                    ['nome', '=', $aDados['nome']],
                    ['active', '=', 1],
                ])->first();

                if ($aResultado)
                    return response()->json($this->toJsonFormatted(true, "Bandeira já existe!", "A bandeira {$aDados['nome']} já está cadastrada em nosso sistema.", ['nome']));
            }

            //Descriptografa o ID da bandeira
            $aDados['id'] = $this->Descriptografia($aDados['id']);

            //Valida se existe a bandeira informada
            $aResultado = Bandeira::where([
                ['id', '=', (int)$aDados['id']],
                ['active', '=', 1],
            ])->first();

            if (!$aResultado)
                return response()->json($this->toJsonFormatted(true, "Bandeira não existe!", "A bandeira {$aDados['nome']} não está cadastrada em nosso sistema.", ['nome']));

            //Converte para array
            $aResultado = $aResultado->toArray();

            //Prepara dados para salvar
            $aData = [
                'nome' => $aDados['nome'] ?? $aResultado['nome'],
                'grupo_economico' => $aDados['grupo'] ?? $aResultado['grupo_economico'],
            ];

            //Atualiza dado na tabela
            if (!$this->edit('bandeiras', $aDados['id'], $aData))
                return response()->json($this->toJsonFormatted(true, "Não foi possível salvar", "Não foi possível salvar o dado no banco de dados. tente novamente ou mais tarde.", $aCamposObrigatorios));

            //Registra ato realizado no sistema
            $this->RegistroMudanca("ATUALIZAÇÃO", $aResultado, $aData, "bandeiras");

            //Retorna View com os itens selecionados
            return response()->json($this->toJsonFormatted(false, "Bandeiras Atualizado!", "O Grupo foi atualizado com sucesso no sistema"));
        }


        /*  
        ===============================================
        MÉTODO: Delete()
        DESCRIÇÃO: Delete dado no banco de dados
        PARÂMETROS: $request (Request) - dados da requisição
        RETORNO: JSON - status da operação
        PROGRAMADOR(A): Ighor Drummond
        DATA: 18/05/2022
        ===============================================
        @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
        */
        public function Delete(Request $request)
        {
            //Declaração de variaveis
            //Array - a
            $aCamposObrigatorios = ['_token', 'nome', 'id'];
            $aDados = [];

            //Redireciona para a tela de login
            if (!Auth::check()) return redirect()->route('/login');

            //Recupera dados enviado pelo formulário
            $aDados = $request->all();

            //Valida campos obrigatórios
            foreach ($aCamposObrigatorios as $Campo) {
                //Valida se o campo existe
                if (!isset($aDados[$Campo]) || empty($aDados[$Campo]))
                    return response()->json($this->toJsonFormatted(true, "Campos Obrigatórios.", "O campo $Campo é obrigatório", [$Campo]));

                //Sanatiza Dados
                $aDados[$Campo] = $this->SanatizaDados($aDados[$Campo]);
            }

            //Descriptografa o ID
            $aDados['id'] = $this->Descriptografia($aDados['id']);

            //Valida se já existe o grupo 
            $aResultado = Bandeira::where([
                ['id', '=', $aDados['id']],
                ['nome', '=', $aDados['nome']],
                ['active', '=', 1],
            ])->first();

            if (!$aResultado)
                return response()->json($this->toJsonFormatted(true, "Bandeira não existe!", "A bandeira {$aDados['nome']} não está cadastrada em nosso sistema", ['nome']));

            //Converte para array
            $aResultado = $aResultado->toArray();

            //Atualiza dado na tabela
            if (!$this->destroy('bandeiras', $aResultado['id']))
                return response()->json($this->toJsonFormatted(true, "Não foi possível deletar", "Não foi possível deletar o dado no banco de dados. tente novamente ou mais tarde.", $aCamposObrigatorios));

            //Registra ato realizado no sistema
            $this->RegistroMudanca("DELEÇÃO", $aResultado, [], "bandeiras");

            //Retorna View com os itens selecionados
            return response()->json($this->toJsonFormatted(false, "Bandeira Apagada!", "A bandeira foi removida com sucesso no sistema."));
        }

        /*  
        ===============================================
        MÉTODO: Formulario()
        DESCRIÇÃO: Carrega dados para listar formulário
        PARÂMETROS: Não há 
        RETORNO: Template - view
        PROGRAMADOR(A): Ighor Drummond
        DATA: 18/05/2022
        ===============================================
        @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
        */
        public function Formulario()
        {
            //Declaração de variaveis
            //Array - a
            $aListGroups = [];

            //Redireciona para a tela de login
            if (!Auth::check()) return "não autorizado!";

            //Lista grupos
            $aListGroups = grupoEconomico::where('active', 1)
                ->orderBy('id', 'DESC')
                    ->get();

            //Valida se tem grupos listados
            forEach($aListGroups as $aGroup){
                $aGroup->id_criptografado = $this->Criptografia($aGroup->id);
            }

            //Criptografa ID dos grupos

            //Retorna View com os itens selecionados
            return view('bandeiras.formulario', compact('aListGroups'));
        }
    }
}