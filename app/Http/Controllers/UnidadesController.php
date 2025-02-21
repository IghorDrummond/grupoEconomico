<?php


/*
==================================================
Namespace: App\Http\Controllers
Descrição: Responsável por agrupar operações relacionadas Unidades
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
    use App\Models\Unidade;
    use App\Traits\JsonFormatted;
    use App\Traits\SecurityDados;
    use App\Traits\EditDatas;
    use App\Traits\Requests;

    /*
    ==================================================
    Classe: UnidadesController
    Extend: Controller
    Descrição: Responsável por realizar operações relacionadas a unidades
    Programador(a): Ighor Drummond
    Data: 19/02/2025
    ==================================================
    Modificações:
    @ Data - Descrição - Programador(a)
    */
    class UnidadesController extends Controller
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
        DATA: 19/05/2022
        ===============================================
        @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
        */
        public function Store()
        {
            //Declaração de variaveis
            //Array - a
            $aListUnity = [];

            //Redireciona para a tela de login
            if (!Auth::check())
                return redirect()->route('/login');

            // Ativa a paginação com Bootstrap 5
            Paginator::useBootstrapFive();

            //Realiza pesquisa de unidades por paginação
            $aListUnity = Unidade::select(
                'unidades.id',
                'unidades.nome_fantasia',
                'unidades.razao_social',
                'unidades.cnpj',
                'bandeiras.nome as bandeira_nome',
                'grupo_economicos.nome as title_group'
            )
                ->join('bandeiras', function ($join) {
                    $join->on('bandeiras.id', '=', 'unidades.bandeira')
                        ->where('bandeiras.active', true);
                })
                ->join('grupo_economicos', function ($join) {
                    $join->on('grupo_economicos.id', '=', 'bandeiras.id')
                        ->where('grupo_economicos.active', true);
                })
                ->where('unidades.active', true)
                ->orderBy('unidades.id', 'DESC')
                ->paginate(10);


            //Realiza criptografia dos IDs das unidades
            $aListUnity->getCollection()->transform(function ($group) {
                $group->id_criptografado = $this->Criptografia($group->id);
                return $group;
            });

            //Retorna View com os itens selecionados
            return view('unidades.index', compact('aListUnity'));
        }

        /*  
        ===============================================
        MÉTODO: Create()
        DESCRIÇÃO: Cria novo dado no banco de dados
        PARÂMETROS: $request (Request) - dados da requisição
        RETORNO: JSON - status da operação
        PROGRAMADOR(A): Ighor Drummond
        DATA: 19/05/2022
        ===============================================
        @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
        */
        public function Create(Request $request)
        {
            //Declaração de variaveis
            //Array - a
            $aCamposObrigatorios = ['_token', 'nome_fantasia', 'bandeira', 'cnpj', 'razao'];
            $aData = [];
            $aDados = [];

            //Redireciona para a tela de login
            if (!Auth::check())
                return redirect()->route('/login');

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

            //Descriptografar ID da bandeira
            $aDados['bandeira'] = $this->Descriptografia($aDados['bandeira']);

            //Valida se existe a bandeira informado
            $aResultado = Bandeira::where([
                ['id', '=', (int) $aDados['bandeira']],
                ['active', '=', 1],
            ])->first();

            if (!$aResultado)
                return response()->json($this->toJsonFormatted(true, "Bandeira não existe!", "A Bandeira informada não está cadastrada em nosso sistema", ['bandeira']));

            //Valida se já existe a unidade criada
            $aResultado = Unidade::where([
                ['nome_fantasia', '=', $aDados['nome_fantasia']],
                ['active', '=', 1],
            ])
                ->orWhere('cnpj', '=', $aDados['cnpj'])
                    ->first();

            if ($aResultado)
                return response()->json($this->toJsonFormatted(true, "Unidade já cadastrada.", "A unidade {$aDados['nome_fantasia']} que você está inserindo já está cadastrada ou o CNPJ já existe em outro cadastro em nosso sistema.", $aCamposObrigatorios));

            //Valida CNPJ
            if (!$this->ValidateCnpj($aDados['cnpj']))
                return response()->json($this->toJsonFormatted(true, "CNPJ Inválido.", "O CNPJ {$aDados['cnpj']} está inválido.", ['cnpj']));

            //Prepara dados para salvar
            $aData = [
                'nome_fantasia' => $aDados['nome_fantasia'],
                'razao_social' => $aDados['razao'],
                'cnpj' => $aDados['cnpj'],
                'bandeira' => $aDados['bandeira']
            ];

            //Insere dados na tabela
            if (!$this->Insert('unidades', $aData))
                return response()->json($this->toJsonFormatted(true, "Não foi possível salvar", "Não foi possível salvar o dado no banco de dados. tente novamente ou mais tarde.", $aCamposObrigatorios));

            //Registra ato realizado no sistema
            $this->RegistroMudanca("INSERÇÃO", [], $aData, "unidades");

            //Retorna View com os itens selecionados
            return response()->json($this->toJsonFormatted(false, "Unidade Criada!", "Adicionado a unidade {$aDados['nome_fantasia']} no sistema."));
        }

        /*  
        ===============================================
        MÉTODO: Update()
        DESCRIÇÃO: Atualiza dado no banco de dados
        PARÂMETROS: $request (Request) - dados da requisição
        RETORNO: JSON - status da operação
        PROGRAMADOR(A): Ighor Drummond
        DATA: 19/05/2022
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
            if (!Auth::check())
                return redirect()->route('/login');

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

            //Valida se foi passado a bandeira
            if (isset($aDados["bandeira"]) and !empty($aDados["bandeira"])) {
                //Descriptografar
                $aDados['bandeira'] = $this->Descriptografia($aDados['bandeira']);

                //Valida se existe uma bandeira informada
                $aResultado = Bandeira::where([
                    ['id', '=', (int) $aDados['bandeira']],
                    ['active', '=', 1],
                ])->first();

                //Retorna inconsistência
                if (!$aResultado)
                    return response()->json($this->toJsonFormatted(true, "Bandeira não existente!", "A bandeira informado não existe no sistema.", ['bandeira']));

            }

            //Valida CNPJ
            if (isset($aDados["cnpj"]) and !empty($aDados["cnpj"])) {
                if (!$this->ValidateCnpj($aDados["cnpj"]))
                    return response()->json($this->toJsonFormatted(true, "Bandeira já existe!", "A bandeira {$aDados['nome']} já está cadastrada em nosso sistema.", ['nome']));

                //Valida se já tem uma unidade com esse cnpj
                $aResultado = Unidade::where('cnpj', '=', $aDados['cnpj'])
                    ->first()
                    ->get();

                if ($aResultado)
                    return response()->json($this->toJsonFormatted(true, "CNPJ já cadastrado!", "O CNPJ informado já está cadastro no sistema.", ['cnpj']));
            }

            // Descriptografa o ID da unidade
            $aDados['id'] = (int) $this->Descriptografia($aDados['id']);

            // Valida se existe a unidade com ID informado
            $aResultado = Unidade::where('id', $aDados['id'])->first();

            if (!$aResultado) {
                return response()->json($this->toJsonFormatted(
                    true,
                    "Unidade não existe!",
                    "A unidade informada não está cadastrada em nosso sistema.",
                    ['nome_fantasia']
                ));
            }

            // Converte para array
            $aResultado = $aResultado->toArray();

            // Prepara dados para salvar
            $aData = [
                'nome_fantasia' => $aDados['nome_fantasia'] ?? $aResultado['nome_fantasia'],
                'razao_social' => $aDados['razao'] ?? $aResultado['razao_social'],
                'cnpj' => $aDados['cnpj'] ?? $aResultado['cnpj'],
                'bandeira' => $aDados['bandeira'] ?? $aResultado['bandeira'],
            ];

            //Atualiza dados na tabela
            if (!$this->edit('unidades', $aDados['id'], $aData))
                return response()->json($this->toJsonFormatted(true, "Não foi possível salvar", "Não foi possível salvar o dado no banco de dados. tente novamente ou mais tarde.", $aCamposObrigatorios));

            //Registra ato realizado no sistema
            $this->RegistroMudanca("ATUALIZAÇÃO", $aResultado, $aData, "unidades");

            //Retorna View com os itens selecionados
            return response()->json($this->toJsonFormatted(false, "Unidade Atualizada!", "A unidade foi atualizada com sucesso no sistema"));
        }


        /*  
        ===============================================
        MÉTODO: Delete()
        DESCRIÇÃO: Delete dado no banco de dados
        PARÂMETROS: $request (Request) - dados da requisição
        RETORNO: JSON - status da operação
        PROGRAMADOR(A): Ighor Drummond
        DATA: 19/05/2022
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
            if (!Auth::check())
                return redirect()->route('/login');

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

            //Valida se já existe a unidade
            $aResultado = Unidade::where([
                ['id', '=', $aDados['id']],
                ['nome_fantasia', '=', $aDados['nome']],
                ['active', '=', 1],
            ])->first();

            if (!$aResultado)
                return response()->json($this->toJsonFormatted(true, "Unidade não existe!", "A unidade {$aDados['nome']} não está cadastrada em nosso sistema"));

            //Converte para array
            $aResultado = $aResultado->toArray();

            //Atualiza dado na tabela
            if (!$this->destroy('unidades', $aResultado['id']))
                return response()->json($this->toJsonFormatted(true, "Não foi possível deletar", "Não foi possível deletar o dado no banco de dados. tente novamente ou mais tarde.", $aCamposObrigatorios));

            //Registra ato realizado no sistema
            $this->RegistroMudanca("DELEÇÃO", $aResultado, [], "unidades");

            //Retorna View com os itens selecionados
            return response()->json($this->toJsonFormatted(false, "Unidade Apagada!", "A unidade foi removida com sucesso no sistema."));
        }

        /*  
        ===============================================
        MÉTODO: Formulario()
        DESCRIÇÃO: Carrega dados para listar formulário
        PARÂMETROS: Não há 
        RETORNO: Template - view
        PROGRAMADOR(A): Ighor Drummond
        DATA: 19/05/2022
        ===============================================
        @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
        */
        public function Formulario()
        {
            //Declaração de variaveis
            //Array - a
            $aListFlags = [];

            //Redireciona para a tela de login
            if (!Auth::check())
                return "não autorizado!";

            //Lista grupos
            $aListFlags = Bandeira::where('active', 1)
                ->orderBy('id', 'DESC')
                ->get();

            //Criptografa ID dos grupos
            foreach ($aListFlags as $aFlags) {
                $aFlags->id_criptografado = $this->Criptografia($aFlags->id);
            }

            //Retorna View com os itens selecionados
            return view('unidades.formulario', compact('aListFlags'));
        }
    }
}
