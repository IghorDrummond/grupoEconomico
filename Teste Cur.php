<?php

$ch = curl_init();

// Dados para enviar no corpo da requisição
$form_data = [
    'name' => 'Ighor Drummond',
    'email' => 'ighordrummond2001@gmail.com',
    'message' => 'Segue seu código de recuperação: 123456',
];

// Definindo as opções do cURL
curl_setopt($ch, CURLOPT_URL, 'https://formsubmit.co/ajax/ighordrummond2001@gmail.com'); // Substitua com seu email
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($form_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
]);

// Executando a requisição
$response = curl_exec($ch);

// Verificando se houve erro na execução
if (curl_errno($ch)) {
    echo 'Erro cURL: ' . curl_error($ch);
} else {
    // Exibe o retorno da requisição
    echo $response;
}

// Fechando a conexão cURL
curl_close($ch);
