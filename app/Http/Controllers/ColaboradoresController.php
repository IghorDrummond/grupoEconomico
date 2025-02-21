<?php

/*
==================================================
Namespace: App\Http\Controllers
Descrição: Responsável por agrupar operações relacionadas a colaboradores
Programador(a): Ighor Drummond
Data: 19/02/2025
==================================================
Modificações:
@ Data - Descrição - Programador(a)
*/
namespace App\Http\Controllers{
    //Bibliotecas obrigatórias 
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Pagination\Paginator;
    use App\Models\Bandeira;
    use App\Models\Colaborador;
    use App\Models\Unidade;
    use App\Traits\JsonFormatted;
    use App\Traits\SecurityDados;
    use App\Traits\EditDatas;
    use App\Traits\Requests;


    /*
    ==================================================
    Classe: ColaboradorController
    Extend: Controller
    Descrição: Responsável por realizar operações relacionadas ao colaborador
    Programador(a): Ighor Drummond
    Data: 19/02/2025
    ==================================================
    Modificações:
    @ Data - Descrição - Programador(a)
    */
    class ColaboradoresController extends Controller
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
            $aListCollab = [];

            //Redireciona para a tela de login
            if (!Auth::check())
                return redirect()->route('/login');

            // Ativa a paginação com Bootstrap 5
            Paginator::useBootstrapFive();

            //Realiza pesquisa de colaboradores por paginação
            $aListCollab = Colaborador::select([
                'colaboradores.id',
                'colaboradores.nome',
                'colaboradores.email',
                'colaboradores.cpf',
                'unidades.nome_fantasia',
                DB::raw("CONCAT(grupo_economicos.nome, ' - ', bandeiras.nome) as title_desc")
            ])
            ->join('unidades', function ($join) {
                $join->on('colaboradores.unidade', '=', 'unidades.id')
                    ->where('unidades.active', true);
            })
            ->join('bandeiras', function ($join) {
                $join->on('bandeiras.id', '=', 'unidades.bandeira')
                    ->where('bandeiras.active', true);
            })
            ->join('grupo_economicos', function ($join) {
                $join->on('grupo_economicos.id', '=', 'bandeiras.grupo_economico')
                    ->where('grupo_economicos.active', true);
            })
                ->where('colaboradores.active', true)
                    ->orderByDesc('colaboradores.id')
                            ->paginate(10);
        
            //Realiza criptografia dos IDs dos colaboradoress
            $aListCollab->getCollection()->transform(function ($group) {
                $group->id_criptografado = $this->Criptografia($group->id);
                return $group;
            });

            //Retorna View com os itens selecionados
            return view('colaboradores.index', compact('aListCollab'));
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
            $aCamposObrigatorios = ['_token', 'unidades', 'nome', 'cpf', 'email'];
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

            //Descriptografar ID das unidades
            $aDados['unidades'] = $this->Descriptografia($aDados['unidades']);

            //Valida se existe a unidade
            $aResultado = Unidade::where([
                ['id', '=', (int) $aDados['unidades']],
                ['active', '=', 1],
            ])->first();

            if (!$aResultado)
                return response()->json($this->toJsonFormatted(true, "Unidade não existe!", "A unidade informada não está cadastrada em nosso sistema", ['unidades']));

            //Valida se já existe um colaborador criado
            $aResultado = Colaborador::where('email', '=', $aDados['email'])
                ->where('active', '=', 1)
                    ->orWhere('cpf', '=', $aDados['cpf'])
                            ->first();

            if ($aResultado)
                return response()->json($this->toJsonFormatted(true, "CPF ou Email já cadastrado.", "Cpf ou Email já está cadastrado em nosso sistema.", $aCamposObrigatorios));

            //Valida CPF
            if (!$this->ValidateCpf($aDados['cpf']))
                return response()->json($this->toJsonFormatted(true, "CPF Inválido.", "O CPF {$aDados['cpf']} está inválido.", ['cpf']));

            //Prepara dados para salvar
            $aData = [
                'nome' => $aDados['nome'],
                'email' => $aDados['email'],
                'cpf' => $aDados['cpf'],
                'unidade' => $aDados['unidades']
            ];

            //Insere dados na tabela
            if (!$this->Insert('colaboradores', $aData))
                return response()->json($this->toJsonFormatted(true, "Não foi possível salvar", "Não foi possível salvar o dado no banco de dados. tente novamente ou mais tarde.", $aCamposObrigatorios));

            //Registra ato realizado no sistema
            $this->RegistroMudanca("INSERÇÃO", [], $aData, "colaboradores");

            //Retorna View com os itens selecionados
            return response()->json($this->toJsonFormatted(false, "Colaborador(a) Criado!", "Adicionado o colaborador(a) {$aDados['nome']} no sistema."));
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

            //Valida se foi passado unidades
            if (isset($aDados["unidades"]) and !empty($aDados["unidades"])) {
                //Descriptografar
                $aDados['unidades'] = $this->Descriptografia($aDados['unidades']);

                //Valida se existe uma bandeira informada
                $aResultado = Unidade::where([
                    ['id', '=', (int) $aDados['unidades']],
                    ['active', '=', 1],
                ])->first();

                //Retorna inconsistência
                if (!$aResultado)
                    return response()->json($this->toJsonFormatted(true, "Unidade não existente!", "A unidade informada não existe no sistema.", ['unidades']));

            }

            //Valida Cpf
            if (isset($aDados["cpf"]) and !empty($aDados["cpf"])) {
                if (!$this->ValidateCpf($aDados["cpf"]))
                    return response()->json($this->toJsonFormatted(true, "Cpf não é valido", "O Cpf {$aDados['nome']} não é valido.", ['cpf']));

                //Valida se já tem uma unidade com esse cnpj
                $aResultado = Colaborador::where('cpf', '=', $aDados['cpf'])
                    ->first()
                        ->get();

                if ($aResultado)
                    return response()->json($this->toJsonFormatted(true, "CPF já cadastrado!", "O CPF {$aDados['cpf']} informado já está cadastro no sistema.", ['cpf']));
            }

            // Descriptografa o ID do colaborador
            $aDados['id'] = (int) $this->Descriptografia($aDados['id']);

            // Valida se existe o colaborador
            $aResultado = Colaborador::where('id', $aDados['id'])->first();

            if (!$aResultado) {
                return response()->json($this->toJsonFormatted(
                    true,
                    "O Colaborador(a) não existe!",
                    "O Colaborador(a) informado não está cadastrada em nosso sistema.",
                    $aCamposObrigatorios
                ));
            }

            // Converte para array
            $aResultado = $aResultado->toArray();

            // Prepara dados para salvar
            $aData = [
                'nome' => $aDados['nome'] ?? $aResultado['nome'],
                'email' => $aDados['email'] ?? $aResultado['email'],
                'cpf' => $aDados['cpf'] ?? $aResultado['cpf'],
                'unidade' => $aDados['unidades'] ?? $aResultado['unidades'],
            ];

            //Atualiza dados na tabela
            if (!$this->edit('colaboradores', $aDados['id'], $aData))
                return response()->json($this->toJsonFormatted(true, "Não foi possível salvar", "Não foi possível salvar o dado no banco de dados. tente novamente ou mais tarde.", $aCamposObrigatorios));

            //Registra ato realizado no sistema
            $this->RegistroMudanca("ATUALIZAÇÃO", $aResultado, $aData, "colaboradores");

            //Retorna View com os itens selecionados
            return response()->json($this->toJsonFormatted(false, "Colaborador(a) Atualizado!", "O Colaborador(a) foi atualizada com sucesso no sistema"));
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

            //Valida se já o colaborador
            $aResultado = Colaborador::where([
                ['id', '=', $aDados['id']],
                ['nome', '=', $aDados['nome']],
                ['active', '=', 1],
            ])->first();

            if (!$aResultado)
                return response()->json($this->toJsonFormatted(true, "Colaborador(a) não existe!", "O Colaborador(a) {$aDados['nome']} não está cadastrada em nosso sistema"));

            //Converte para array
            $aResultado = $aResultado->toArray();

            //Atualiza dado na tabela
            if (!$this->destroy('colaboradores', $aResultado['id']))
                return response()->json($this->toJsonFormatted(true, "Não foi possível deletar", "Não foi possível deletar o dado no banco de dados. tente novamente ou mais tarde.", $aCamposObrigatorios));

            //Registra ato realizado no sistema
            $this->RegistroMudanca("DELEÇÃO", $aResultado, [], "colaboradores");

            //Retorna View com os itens selecionados
            return response()->json($this->toJsonFormatted(false, "Colaborador(a) Apagado!", "O Colabaroador(a) foi removido com sucesso no sistema."));
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
            $aListUnity = [];

            //Redireciona para a tela de login
            if (!Auth::check())
                return "não autorizado!";

            //Lista grupos
            $aListUnity = Unidade::where('active', 1)
                ->orderBy('id', 'DESC')
                    ->get();

            //Criptografa ID dos grupos
            foreach ($aListUnity as $aUnity) {
                $aUnity->id_criptografado = $this->Criptografia($aUnity->id);
            }

            //Retorna View com os itens selecionados
            return view('colaboradores.formulario', compact('aListUnity'));
        }
    }
}
