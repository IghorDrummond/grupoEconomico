<?php

/*
==================================================
Namespace: App\Http\Controllers
Descrição: Responsável por agrupar operações relacionadas a rotas da aplicação
Programador(a): Ighor Drummond
Data: 15/02/2025
==================================================
Modificações:
@ Data - Descrição - Programador(a)
*/
namespace App\Http\Controllers{
    //Bibliotecas obrigatórias 
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Session;
    use App\Models\User;
    use App\Models\passwordResetToken;
    use App\Traits\JsonFormatted;
    use App\Traits\SecurityDados;
    use App\Traits\EditDatas;
    use App\Traits\Requests;
    use App\Traits\MailConfig;
    
    /*
    ==================================================
    Classe: UsuariosController
    Extend: Controller
    Descrição: Responsável por realizar operações relacionadas ao usuários
    Programador(a): Ighor Drummond
    Data: 15/02/2025
    ==================================================
    Modificações:
    @ Data - Descrição - Programador(a)
    */
    class UsuariosControllers extends Controller
    {
        //Incluí o Traits para serem usadas no controller
        use JsonFormatted;
        use SecurityDados;
        use EditDatas;
        use Requests;
        use MailConfig;

        //Destruidor
        public function __destruct(){
            Log::channel('registroData')->info("Encerrado operação no Controller Usuarios.");
        }

        /*
        ================================================
        Métodos: Index()
        Descrição: Responsável por renderizar a tela de Login
        Parâmetros: Não há
        Retorno: Template - Html
        Programador(a): Ighor Drummond
        Data: 15/02/2025
        ================================================
        @ Data - Descrição - Programador(a)
        */
        public function index(){
            //Valida se o usuário já está logado no sistema
            if (Auth::check()) return redirect()->route('home');
                   
            return view("login/index");
        }

        /*
        ================================================
        Métodos: Index_home()
        Descrição: Responsável por renderizar a tela inicial
        Parâmetros: Não há
        Retorno: Template - Html
        Programador(a): Ighor Drummond
        Data: 15/02/2025
        ================================================
        @ Data - Descrição - Programador(a)
        */
        public function Index_home(){
            //Valida se o usuário não está logado no sistema
            if (!Auth::check()) return redirect()->route('/login');
                   
            return view("home/index");
        }

        /*
        ================================================
        Métodos: Store()
        Descrição: Responsável por realizar o Login do usuário no sistema
        Parâmetros: Não há
        Retorno: JSON
        Programador(a): Ighor Drummond
        Data: 15/02/2025
        ================================================
        @ Data - Descrição - Programador(a)
        */
        public function Store(Request $request){
            //Declaração de variaveis
            //Array - a
            $aCamposObrigatorios = ['email', 'password', '_token'];
            $aDados = [];


            //Valida se o usuário já está logado no sistema
            if (Auth::check()) return redirect()->route('home');

            //Recupera dados do formulário  
            $aDados = $request->all();

            //Sanatiza dados
            forEach($aCamposObrigatorios as $Campos){
                //Valida campos Obrigatórios
                if(!isset($aDados[$Campos]) || empty($aDados[$Campos])){
                    //Retorna erro caso um dos campos obrigatórios estiverem faltando
                    return response()->json($this->toJsonFormatted(true, "Dados faltando!" ,"Campo {$Campos} Não preenchido ou faltando.", [$Campos]));
                }

                //Sataniza dado
                $aDados[$Campos] = $this->SanatizaDados($aDados[$Campos]);
            }

            // Valida se as credenciais estão corretas
            if (Auth::attempt(['email' => $aDados['email'], 'password' => $aDados['password']])) {
                // Regenera a sessão para evitar ataques de fixation
                $request->session()->regenerate();

                // Redireciona para página Home
                return response()->json($this->toJsonFormatted(false, "Acesso válidado com sucesso!", "Login efetuado com sucesso!"));
            }

            //Retorna erro caso as credenciais estejam incorretas
            return response()->json($this->toJsonFormatted(true, "Login Invalido!", "Email ou senha incorretos",['email', 'password']));
        }

        /*
        ================================================
        Métodos: Create()
        Descrição: Responsável por criar um novo usuário ao sistema
        Parâmetros: Não há
        Retorno: JSON
        Programador(a): Ighor Drummond
        Data: 17/02/2025
        ================================================
        @ Data - Descrição - Programador(a)
        */
        public function Create(Request $request){
            //Declaração de variaveis
            //Array - a
            $aCamposObrigatorios = ['email', 'name', 'lastname', 'birth', 'confirmPassword', 'cpf' ,'password', '_token'];
            $aDados = [];
            $aResultado = [];

            //Valida se o usuário já está logado no sistema
            if (Auth::check()) return redirect()->route('home');

            //Recupera dados do formulário  
            $aDados = $request->all();

            //Sanatiza dados
            forEach($aCamposObrigatorios as $Campos){
                //Valida campos Obrigatórios
                if(!isset($aDados[$Campos]) || empty($aDados[$Campos])){
                    //Retorna erro caso um dos campos obrigatórios estiverem faltando
                    return response()->json($this->toJsonFormatted(true, "Dados faltando!" ,"Campo {$Campos} Não preenchido ou faltando.", [$Campos]));
                }

                //Sataniza dados
                $aDados[$Campos] = $this->SanatizaDados($aDados[$Campos]);
            }

            //Valida se já existe um usuário cadastrado com cpf ou email
            $aResultado = User::where('email', $aDados['email'])
                ->orWhere('cpf', str_replace('-','', $aDados['cpf']))
                    ->get()
                        ->first();

                    
            if(!empty($aResultado)) return response()->json($this->toJsonFormatted(true, "Usuário Já cadastrado." ,"Usuário com email {$aDados['email']} já está cadastrado no sistema.",  $aCamposObrigatorios));
            
            //Valida se as senhas se condizem
            if($aDados['password'] !== $aDados['confirmPassword']) return response()->json($this->toJsonFormatted(true,"Senhas não se condizem.", "as senhas inseridas não estão idênticas.", ['confirmPassword', 'password']));

            //Monta dados para inserção
            $aInserir = [
                'name' => mb_convert_case($aDados['name'], MB_CASE_TITLE, "UTF-8"),
                'email'=> $aDados['email'],
                'lastname' => mb_convert_case($aDados['lastname'], MB_CASE_TITLE, "UTF-8"),
                'cpf' => trim(preg_replace('/[.-]/', '', $aDados['cpf'])),
                'birth' => date('Y-m-d', strtotime($aDados['birth'])),
                'password' => Hash::make($aDados['password']),
            ];

            //Insere novo dado ao banco de dados
            if(!$this->Insert('users', $aInserir)) return response()->json($this->toJsonFormatted(true,"Não foi possível adicionar usuário", "Não foi possível adicionar o novo usuário devido a uma inconsistência interna. Tente novamente ou mais tarde.", $aCamposObrigatorios));

            //Redireciona para página Login após Logado no sistema
            return response()->json($this->toJsonFormatted(false,"Usuário cadastrado com sucesso!", "Volte para tela de Login para realizar seu acesso em nossa plataforma", [], "/login"));
        }

        /*
        ================================================
        Métodos: Reset()
        Descrição: Responsável por resetar uma senha
        Parâmetros: Não há
        Retorno: JSON
        Programador(a): Ighor Drummond
        Data: 17/02/2025
        ================================================
        @ Data - Descrição - Programador(a)
        */
        public function Reset(Request $request){
            //Declaração de variaveis
            //String - c
            $cCodigo = "";
            $cUrl = "";
            //Array - a
            $aCamposObritarios = ['_token'];
            $aDados = [];
            $aResultado = [];
            $aData = [];
            $aKeys = [];
            //Date - d
            $dDataAtual = new \Datetime();

            //Valida se o usuário já está logado no sistema
            if (Auth::check()) return redirect()->route('home');

            //Recupera dados do formulário  
            $aDados = $request->all();

            //Valida se foi enviado o token CSRF
            if(!isset($aDados["_token"])) return response()->json($this->toJsonFormatted(true,"Falta Token!", "Não há token de autorização para essa requisição"));

            //Sanatiza dados
            $aKeys = array_keys($aDados);

            forEach($aKeys as $key){
                $aDados[$key] = $this->SanatizaDados($aDados[$key]);
            }
            
            //Valida se é inserção de email
            if(isset($aDados['email'])){

                //Sanatiza o email
                $aDados['email'] = $this->SanatizaDados($aDados['email']);

                //Valida se a conta existe
                $aResultado = User::where('email', $aDados['email'])->first();

                if(!$aResultado) return response()->json($this->toJsonFormatted(true,"Conta não existe", "Usuário não cadastrado",  ['email']));
                
                //Converte em array
                $aResultado = $aResultado->toArray();

                //Cria um código para recuperação
                for($nCont = 0; $nCont <= 5; $nCont++){
                    $cCodigo .= strval(mt_rand(1,  9));
                }

                //Monta corpo para adicionar refresh de senha
                $aData = [
                    'email' => $aResultado['email'],
                    'token' => Hash::make($cCodigo),
                    'user_id' => $aResultado['id']
                ];

                //Cria sessão para guarda email de recuperação
                Session::put('password_refresh', $aResultado);
            
                //Guarda código de recuperação
                if(!$this->Insert("password_reset_tokens", $aData)) return response()->json($this->toJsonFormatted(true,"Não foi possível enviar o email.", "Não foi possível enviar o email pois houve uma inconsistência interna. tente novamente ou mais tarde")); ;

                //Monta corpo do email para recuperação de senha
                $cBody = $this->MailBody(0, ['codigo' => $cCodigo, "NomeCompleto" => "{$aResultado['name']} {$aResultado['lastname']}"]);

                //Prepara dados para requisição API
                $aData = $this->MailSend( "{$aResultado['name']} {$aResultado['lastname']}", $aResultado['email'], "Recuperação de senha - Grupo Econômico", $cBody);

                //Montar o Header
                $aHeader = $this->MailHeader();

                //Envia Email.
                $aResposta = $this->CalledUrl($aData, $aHeader, "https://api.brevo.com/v3/smtp/email", "POST");

                if(!$aResposta || (isset($aResposta['code']) and $aResposta['code'] === 'missing_parameter')) return response()->json($this->toJsonFormatted(true,"Não foi possível enviar o email.", "Não foi possível enviar o email pois houve uma inconsistência interna. tente novamente ou mais tarde"));

                //Retorna tudo Ok
                return response()->json($this->toJsonFormatted(false,"Email Enviado.", "O email de recuperação foi enviado com sucesso para sua caixa postal. valide a caixa de spam ou lixeira.", [], "/envia-codigo"));
            }else if(isset($aDados['codigo'])){
                //Valida se o código informado é válido
                $aResultado = Session::get('password_refresh');

                //Limpa formato de código
                $aDados['codigo'] = preg_replace('/\D/', '', $aDados['codigo']);

                //Recupera token gerado
                $aResultado = passwordResetToken::where('email', $aResultado['email'])
                    ->where('user_id', $aResultado['id'])
                        ->orderBy('id','desc')
                            ->first();

                if(!$aResultado) return response()->json($this->toJsonFormatted(true,"Não foi possível encontrar o email.", "Não foi possível encontrar o email pois houve uma inconsistência interna. tente novamente ou mais tarde"));

                $aResultado = $aResultado->toArray();

                //Valida token se são iguais
                if(!Hash::check((int)$aDados['codigo'], $aResultado['token'])) return response()->json($this->toJsonFormatted(true,"Código Inválido!", "O código inserido está inválido.", ['codigo']));

                //Valida se o token está vencido
                $aResultado['created_at'] = new \Datetime($aResultado['created_at']);

                $diff = $aResultado['created_at']->diff($dDataAtual);

                if($diff > $aResultado['created_at'] and $diff->i > 10) return response()->json($this->toJsonFormatted(true,"Código vencido!", "O código ao qual você gerou já está vencido. Gere novamente para recupera sua senha", ['codigo'], '/login'));

                //Pega a sessão e validando a troca de senha deste usuário
                Session::put('password_refresh', ['user_id' => $aResultado['user_id'], 'status' => true, 'date_generation'=> $dDataAtual, 'token_id' => $aResultado['id']]);

                //Retorna Ok redirecionando para troca senha
                return response()->json($this->toJsonFormatted(false,"Código aceito!", "Código validado. Troque sua senha com segurança respeitando as regras solicitadas.", [], '/troca-senha'));
            }else if($aDados['password']){

                //Valida se foram passado as senhas
                if(
                    !isset($aDados['confirmPassword']) || empty($aDados['confirmPassword'])
                    || !isset($aDados['password']) || empty($aDados['password'])
                ){
                    return response()->json($this->toJsonFormatted(true,"Senhas inválidas!", "As senhas digitas são inválidas!", ['password', 'confirmPassword']));
                }

                //Valida se as senhas estão iguais
                if($aDados['password'] !== $aDados['confirmPassword']) return response()->json($this->toJsonFormatted(true,"Senhas inválidas!", "As senhas digitas são inválidas!", ['password', 'confirmPassword']));

                //Recupera dados da sessão
                $aResultado = Session::get('password_refresh');

                //Monta corpo para trocar a senha já criptografada
                $aData = [
                    'password' => Hash::make($aDados['password'])
                ];

                if(!$this->edit("users", $aResultado['user_id'] ,$aData)) return response()->json($this->toJsonFormatted(true,"Ocorreu uma inconsistência!", "Houve uma inconsistência internar. tente novamente ou mais tarde."));

                //Retorna Ok redirecionando para login
                return response()->json($this->toJsonFormatted(false,"Senha atualizada!", "A senha foi atualizada com sucesso! Faça um login com sua nova senha para acessar nosso sistema.", [], '/login'));
            }
        }

        /*
        ================================================
        Métodos: Logoutoff
        Descrição: Desloga usuário da plataforma
        Parâmetros: Não há
        Retorno: Redirecionamento - Login
        Programador(a): Ighor Drummond
        Data: 17/02/2025
        ================================================
        @ Data - Descrição - Programador(a)
        */
        public function Logoutoff(){
            //Realiza Logoff do Usuário a plataforma
            if (Auth::check()){
                Auth::logout();
            };

            //Redireciona para tela de login
            return redirect()->route('login');
        }
    }
}
