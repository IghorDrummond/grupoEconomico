<?php

/*
==================================================
Namespace: App\Http\Controllers
Descrição: Responsável por agrupar operações relacionadas Grupos Econômicos
Programador(a): Ighor Drummond
Data: 18/02/2025
==================================================
Modificações:
@ Data - Descrição - Programador(a)
*/
namespace App\Http\Controllers{
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Pagination\Paginator;
    use App\Models\User;
    use App\Models\grupoEconomico;
    use App\Traits\JsonFormatted;
    use App\Traits\SecurityDados;
    use App\Traits\EditDatas;
    use App\Traits\Requests;
    

    /*
    ==================================================
    Classe: GrupoController
    Extend: Controller
    Descrição: Responsável por realizar operações relacionadas ao Grupos Econômicos
    Programador(a): Ighor Drummond
    Data: 18/02/2025
    ==================================================
    Modificações:
    @ Data - Descrição - Programador(a)
    */
    class GrupoController extends Controller
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
            $aListGroups = [];
    
            //Redireciona para a tela de login
            if (!Auth::check()) return redirect()->route('/login');
    
            // Ativa a paginação com Bootstrap 5
            Paginator::useBootstrapFive();
    
            //Realiza pesquisa de grupos por paginação
            $aListGroups = $aListGroups = grupoEconomico::where('active', 1)
                ->orderBy('id', 'DESC')
                    ->paginate(15);
    
            //Realiza criptografia dos IDs do grupo
            $aListGroups->getCollection()->transform(function ($group) {
                $group->id_criptografado = $this->Criptografia($group->id);
                return $group;
            });
    
            //Retorna View com os itens selecionados
            return view('grupo_economicos.index', compact('aListGroups'));
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
            $aCamposObrigatorios = ['_token', 'nome'];
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
    
            //Valida se já existe o grupo 
            $aResultado = grupoEconomico::where([
                ['nome', '=', $aDados['nome']],
                ['active', '=', 1],
            ])->first();
    
            if ($aResultado)
                return response()->json($this->toJsonFormatted(true, "Grupo já cadastro.", "O Grupo {$aDados['nome']} já está cadastrado no sistema.", $aCamposObrigatorios));
    
            //Prepara dados para salvar
            $aData = [
                'nome' => $aDados['nome'],
            ];
    
            //Insere dados na tabela
            if (!$this->Insert('grupo_economicos', $aData))
                return response()->json($this->toJsonFormatted(true, "Não foi possível salvar", "Não foi possível salvar o dado no banco de dados. tente novamente ou mais tarde.", $aCamposObrigatorios));
    
            //Registra ato realizado no sistema
            $this->RegistroMudanca("INSERÇÃO", [], $aData, "grupo_economicos");
    
            //Retorna View com os itens selecionados
            return response()->json($this->toJsonFormatted(false, "Grupo Criado!", "Adicionado o grupo {$aDados['nome']} no sistema."));
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
            $aCamposObrigatorios = ['_token', 'nome', 'id'];
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
    
            //Descriptografa o ID
            $aDados['id'] = $this->Descriptografia($aDados['id']);
    
            //Valida se já existe um grupo com este nome
            $aResultado = grupoEconomico::where([
                ['nome', '=', $aDados['nome']],
                ['active', '=', 1],
            ])->first();
    
            if ($aResultado)
            return response()->json($this->toJsonFormatted(true, "Grupo com nome já existente!", "O Grupo {$aDados['nome']} já existe no sistema.", $aCamposObrigatorios));
    
            //Valida se existe o grupo informado
            $aResultado = grupoEconomico::where([
                ['id', '=', (int)$aDados['id']],
                ['active', '=', 1],
            ])->first();
    
            if (!$aResultado)
                return response()->json($this->toJsonFormatted(true, "Grupo não existe!", "O Grupo {$aDados['nome']} não está cadastrado no sistema", $aCamposObrigatorios));
    
            //Converte para array
            $aResultado = $aResultado->toArray();
    
            //Prepara dados para salvar
            $aData = [
                'nome' => $aDados['nome']
            ];
    
            //Atualiza dado na tabela
            if (!$this->edit('grupo_economicos', $aDados['id'], $aData))
                return response()->json($this->toJsonFormatted(true, "Não foi possível salvar", "Não foi possível salvar o dado no banco de dados. tente novamente ou mais tarde.", $aCamposObrigatorios));
    
            //Registra ato realizado no sistema
            $this->RegistroMudanca("ATUALIZAÇÃO", $aResultado, $aData, "grupo_economicos");
    
            //Retorna View com os itens selecionados
            return response()->json($this->toJsonFormatted(false, "Grupo Atualizado!", "O Grupo foi atualizado com sucesso no sistema"));
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
            $aResultado = grupoEconomico::where([
                ['id', '=', $aDados['id']],
                ['nome', '=', $aDados['nome']],
                ['active', '=', 1],
            ])->first();
    
            if (!$aResultado)
                return response()->json($this->toJsonFormatted(true, "Grupo não existe!", "O Grupo {$aDados['nome']} não está cadastrado no sistema", $aCamposObrigatorios));
    
            //Converte para array
            $aResultado = $aResultado->toArray();
    
            //Atualiza dado na tabela
            if (!$this->destroy('grupo_economicos', $aResultado['id']))
                return response()->json($this->toJsonFormatted(true, "Não foi possível salvar", "Não foi possível salvar o dado no banco de dados. tente novamente ou mais tarde.", $aCamposObrigatorios));
    
            //Registra ato realizado no sistema
            $this->RegistroMudanca("DELEÇÃO", $aResultado, [], "grupo_economicos");
    
            //Retorna View com os itens selecionados
            return response()->json($this->toJsonFormatted(false, "Grupo Atualizado!", "O Grupo foi atualizado com sucesso no sistema"));
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
            //Redireciona para a tela de login
            if (!Auth::check()) return "não autorizado!";
    
            //Retorna View com os itens selecionados
            return view('grupo_economicos.formulario');
        }
    }    
}
