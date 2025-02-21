<?php

namespace App\Traits;

trait JsonFormatted
{
    /*
    ================================================
    Métodos: toJsonFormatted
    Descrição: Formata Json para retorno
    Parâmetros: Diversos
    Retorno: $aRet - (array)
    Programador(a): Ighor Drummond
    Data: 17/02/2025
    ================================================
    @ Data - Descrição - Programador(a)
    */
    public function toJsonFormatted(
        bool $lType = false,
        string $cTitle = "",
        string $cMessage = "",
        array $aData = [],
        string $cRedirect = ""
    ): array
    {
        //Declaração de variaveis
        //Array - a
        $aRet = ["error" => [], "title" => $cTitle ,"message"=> $cMessage];

        //Retorna se deu erro ou não
        $aRet["error"] = $lType ? true : false;

        //Retorna dados caso houver
        count($aData) > 0 ? $aRet['data'] = $aData : null;

        //Verifica se tem um redirect a ser realizado
        if(!empty($cRedirect)) $aRet['redirect'] = $cRedirect;

        //Retorna Array formatado para Json
        return $aRet;
    }
}
