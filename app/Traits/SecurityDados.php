<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;

trait SecurityDados
{
    //Constantes
    const IV = "�k��IB��t�{vY";//Chave IV
    const KEY = "(id4!GJg5çÇb$1;vVKOm2*'Yo[%x9>S(=y$9)n6L6£+L\V9RQV%";//Chave da criptografia
    const CIPHER = 'AES-256-CBC'; // Algoritmo de criptografia


    /*
    ================================================
    Métodos: SanatizaDados()
    Descrição: Sanatiza Dados do seu tipo
    Parâmetros: [($uDado) - Formata Dado para seu tipo ]
    Retorno: $uRet - retorna dado sanatizado
    Programador(a): Ighor Drummond
    Data: 17/02/2025
    ================================================
    @ Data - Descrição - Programador(a)
    */
    public function SanatizaDados($uDado = null){
        //Sanatiza o dado pelo seu tipo e retorna ao Controller
        return match ($uDado){
            is_string($uDado) => filter_var($uDado, FILTER_SANITIZE_STRING),//Sanatiza String
            is_int($uDado) => filter_var($uDado, FILTER_VALIDATE_INT),//Sanatiza Integer
            is_float($uDado) => filter_var($uDado, FILTER_VALIDATE_INT),//Sanatiza float
            is_array($uDado) => filter_var($uDado, FILTER_SANITIZE_STRING),//Sanatiza Array
            is_object($uDado) => filter_var($uDado, FILTER_SANITIZE_STRING),//Sanatiza Object
            is_bool($uDado) => filter_var($uDado, FILTER_VALIDATE_BOOLEAN),//Sanatiza Boolean
            default => $uDado//Retorna valor padrão caso não houver sanatização definida
        };
    }

    /*
    ================================================
    Métodos: Criptografia()
    Descrição: Realiza criptografia de um dado
    Parâmetros: [($cDado) string - Dado a ser criptografado ]
    Retorno: ($cRet) string - retorna dado criptografado
    Programador(a): Ighor Drummond
    Data: 17/02/2025
    ================================================
    @ Data - Descrição - Programador(a)
    */
    public function Criptografia(string $cDado = ""): string {
        // Declaração de variáveis
        $cRet = "";
    
        // Certifica-se que o IV tem exatamente 16 bytes
        $iv = substr(self::IV, 0, 16); // Trunca ou ajusta o IV para 16 bytes
    
        // Criptografar o dado
        $cRet = openssl_encrypt($cDado, self::CIPHER, self::KEY, OPENSSL_RAW_DATA, $iv);
    
        // Verifica se a criptografia falhou
        if (is_bool($cRet)) return false;
    
        // Retorna a criptografia em base64
        return base64_encode($cRet);
    }

    /*
    ================================================
    Métodos: Descriptografia()
    Descrição: Realiza uma descriptografia do dado
    Parâmetros: [($cDado) string - Dado a ser criptografado ]
    Retorno: ($cRet) string - retorna dado descriptografado
    Programador(a): Ighor Drummond
    Data: 17/02/2025
    ================================================
    @ Data - Descrição - Programador(a)
    */
    public function Descriptografia(string $cDado = ""): string{
        //Descriptografa a codificação
        return openssl_decrypt(base64_decode($cDado), self::CIPHER, self::KEY, OPENSSL_RAW_DATA, substr(self::IV, 0, 16));
    }

    /*
    ================================================
    Métodos: RegistroMudanca
    Descrição: Guarda a alteração do registro
    Parâmetros: Diversos
    Retorno: Não há - void
    Programador(a): Ighor Drummond
    Data: 18/02/2025
    ================================================
    @ Data - Descrição - Programador(a)
    */
    public function RegistroMudanca(
        string $cOpc = "",//Operação realizada na tabela 
        array $aDadoAnt = [],//Dado anterior 
        array $aDadoDep = [],//Dado posterior
        string $cTable = ""//Nome da tabela que foi alterada
    ): void{
        
        //Prepara dado para inserção do registro
        $aData = [
            'user_id' => Auth::user()->id,
            'operacao' => $cOpc,
            'tabela_alterada' => $cTable,
            'dado_anterior' => json_encode($aDadoAnt, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'dado_atual' => json_encode($aDadoDep, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        ]; 

        //Insere na tabela o novo registro
        $this->Insert('registro', $aData);
    }

    /*
    ================================================
    Métodos: ValidateCnpj
    Descrição: Valida CNPJ informado
    Parâmetros: $cCnpj (string) - Cnpj a ser validado
    Retorno: $lRet (bool) - Retorna true se válido, false se inválido
    Programador(a): Ighor Drummond
    Data: 19/02/2025
    ================================================
    @ Data - Descrição - Programador(a)
    */
    public function ValidateCnpj(
        string $cCnpj = "",
    ): bool{
        //Declaração de variaveis
        //String - c

        //Integer - n
        $nTotal = 0;
        $nDigito = 0;
        $nCont = 0;
        $nTam = 11;
        //Array - a
        $aPeso = [[5,4,3,2,9,8,7,6,5,4,3,2], [6,5,4,3,2,9,8,7,6,5,4,3,2]];
        //Boolean - l
        $lRet = false;

        //Formata cnpj para apenas números
        $cCnpj = preg_replace('/\D/', '', $cCnpj); 

        //Validar se todos os números são iguais
        for($nCont = 0; $nCont <= strlen($cCnpj) -1; $nCont++){
            if( substr($cCnpj, $nCont, 1) !== substr($cCnpj, ( $nCont === 0 ? 0 : ($nCont - 1) ), 1) ){
                $lRet = true;
                break;
            }
        }

        //Caso o cnpj estiver com todos os números iguais, já retorna falso
        if(!$lRet) return false;

        //---------------Calcular o primeiro digito verificador
        for($nCont = 0; $nCont <= 1; $nCont++){
            //Valida cada digíto 
            $nTotal = $this->ValidateDigit($nTam, $cCnpj, $aPeso[$nCont]);

            //Definindo o primeiro digito do CNPJ
            $nDigito = ($nTotal%11) >= 2 ? abs(($nTotal%11) - 11): 0;

            //Valida e foi validado o primeiro digito
            if($nDigito !== (int)substr($cCnpj,  $nTam + 1,1)) return false;

            $nTam++;
        }

        //Retorna ok se estiver tudo joia
        return true;
    }

    /*
    ================================================
    Métodos: ValidateCpf
    Descrição: Valida Cpf informado
    Parâmetros: $cCpf (string) - Cpf a ser validado
    Retorno: $lRet (bool) - Retorna true se válido, false se inválido
    Programador(a): Ighor Drummond
    Data: 19/02/2025
    ================================================
    @ Data - Descrição - Programador(a)
    */
    public function ValidateCpf(
        string $cCpf = "",
    ): bool{
        //Declaração de variaveis
        //String - c

        //Integer - n
        $nTotal = 0;
        $nDigito = 0;
        $nCont = 0;
        $nTam = 8;
        //Array - a
        $aPeso = [[10,9,8,7,6,5,4,3,2], [11,10,9,8,7,6,5,4,3,2]];
        //Boolean - l
        $lRet = false;

        //Formata cpf para apenas números
        $cCpf = preg_replace('/\D/', '', $cCpf); 

        //Validar se todos os números são iguais
        for($nCont = 0; $nCont <= strlen($cCpf) -1; $nCont++){
            if( substr($cCpf, $nCont, 1) !== substr($cCpf, ( $nCont === 0 ? 0 : ($nCont - 1) ), 1) ){
                $lRet = true;
                break;
            }
        }

        //Caso o cpf estiver com todos os números iguais, já retorna falso
        if(!$lRet) return false;

        //---------------Calcular o primeiro digito verificador
        for($nCont = 0; $nCont <= 1; $nCont++){
            //Valida cada digíto 
            $nTotal = $this->ValidateDigit($nTam, $cCpf, $aPeso[$nCont]);

            //Definindo o primeiro digito do CNPJ
            $nDigito = ($nTotal%11) >= 2 ? abs(($nTotal%11) - 11): 0;

            //Valida e foi validado o primeiro digito
            if($nDigito !== (int)substr($cCpf,  $nTam + 1,1)) return false;

            $nTam++;
        }

        //Retorna ok se estiver tudo joia
        return true;
    }

    /*
    ================================================
    Métodos: ValidateDigit
    Descrição: Valida digitos do CNPJ
    Parâmetros: Diversos
    Retorno: $nRet (int) - Retorna o total da conta
    Programador(a): Ighor Drummond
    Data: 19/02/2025
    ================================================
    @ Data - Descrição - Programador(a)
    */
    private function ValidateDigit(int $nTam = 0, string $cCnpj = "", array $aPeso =[]): int{
        //Declaração de variaveis
        //Integer - n
        $nRet = 0;
        $nCont = 0;

        for($nCont = 0; $nCont <= $nTam; $nCont++){
            $nRet += ((int)substr($cCnpj, $nCont, 1) *  $aPeso[$nCont]);
        }

        return $nRet;
    }
}