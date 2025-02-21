<?php

namespace App\Traits;

use function GuzzleHttp\json_encode;

trait Requests
{

    /*
    ================================================
    Métodos: CalledUrl()
    Descrição: Realiza requisições HTTP/HTTPS para o servidor
    Parâmetros: Diversos
    Retorno: $aRet - (array)
    Programador(a): Ighor Drummond
    Data: 17/02/2025
    ================================================
    @ Data - Descrição - Programador(a)
    */
    public function CalledUrl(
        array $aData = [],
        array $aHeaders = [],
        string $cUrl = "",
        string $cType = ""
    ): array{
        //Declaração de variaveis
        //Array
        $aRet = [];
        //Objeto
        $oCh = null;

        //Declaração de variaveis
        $oCh = curl_init();

        // Configurações básicas da requisição cURL
        curl_setopt($oCh, CURLOPT_URL, $cUrl);//Define a URL da requisição
        curl_setopt($oCh, CURLOPT_RETURNTRANSFER, true); // Retorna o resultado como string
        curl_setopt($oCh, CURLOPT_TIMEOUT, 30); // Timeout de 30 segundos
 
        //Cabeçalho da requisição
        if(count($aHeaders) > 0) curl_setopt($oCh, CURLOPT_HTTPHEADER, $aHeaders);

        //Valida tipo da requisição
        curl_setopt($oCh, CURLOPT_CUSTOMREQUEST,  $cType); // Método POST

        //Valida se tem corpo para enviar na requisição
        if($aData) curl_setopt($oCh, CURLOPT_POSTFIELDS, json_encode($aData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)); 
        
        //Executa a requisição
        $response = curl_exec($oCh);

        //Encerra a requisiçãso
        curl_close($oCh);

        // Verifica se ocorreu algum erro na requisição
        if(curl_errno($oCh)) {
            echo 'Erro cURL: ' . curl_error($oCh);
            return $aRet;
        } 
            
        //Converte a resposta para Array
        $aRet = json_decode($response, true);

        //Valida se a conversão ocorreu tudo Ok
        if(!is_array($aRet)) return $aRet;
        
        return $aRet; 
    }

}
