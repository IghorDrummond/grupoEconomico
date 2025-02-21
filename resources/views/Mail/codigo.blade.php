<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Código de Recuperação</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                padding: 20px;
                text-align: center;
            }
            .container {
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                max-width: 500px;
                margin: auto;
            }
            .code {
                font-size: 24px;
                font-weight: bold;
                background-color: #f8f9fa;
                padding: 10px;
                border-radius: 5px;
                display: inline-block;
                margin-top: 10px;
            }
            .footer {
                font-size: 12px;
                color: #555;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Olá, {{ $aDados['NomeCompleto'] }}</h1>

            <h2>🛡️ Segurança em Primeiro Lugar!</h2>
            <p>Você solicitou a recuperação de senha. Use o código abaixo para redefinir sua senha:</p>
            <div class="code">{{ $aDados['codigo'] }}</div>
            <p><strong>⚠️ Atenção:</strong> Nunca compartilhe este código com ninguém. Se você não solicitou a recuperação, ignore este e-mail.</p>
            <hr>
            <p class="footer">Este e-mail foi enviado automaticamente. Caso tenha dúvidas, entre em contato com o desenvolvedor.</p>
        </div>
    </body>
</html>
