<?php

namespace App\Traits;

use View;

trait MailConfig
{
    //Constantes
    const MAIL_SENDER_NAME = 'Grupo Econômico';
    const MAIL_SENDER_EMAIL = 'ighordrummond.cedaspy@gmail.com';
    const API_KEY = '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';

    public function MailSend(string $cNome = "", string $cEmail = "", string $cSubject = "", string $cBody = ""): array
    {
        //Declaração de variaveis
        //Array - a
        $aAjson = [];

        $aJson = [
            'sender' => [
                'name'  => self::MAIL_SENDER_NAME,
                'email' => self::MAIL_SENDER_EMAIL
            ],
            'to' => [
                [
                    'name'  => $cNome,
                    'email' => $cEmail
                ]
            ],
            'subject' => $cSubject,
            'htmlContent' => $cBody
        ];

        return $aJson;
    }

    public function MailHeader()
    {
        //Declaração de variaveis
        //Array
        $aHeaders = [
            'accept: application/json',
            'api-key:' . self::API_KEY,
            'content-type: application/json'
        ];

        return $aHeaders;
    }

    public function MailBody(int $cOpc = 0, array $aDados): string
    {
        //Envio de código de recuperação
        if ($cOpc === 0)
            return View('Mail.codigo', compact('aDados'))->render();

        return  '<h1>Sou um email do Grupo Econômico</h1>';
    }
}
