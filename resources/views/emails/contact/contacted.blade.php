<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        h1 {
            color: #4CAF50;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Solicitação de contato</h1>

        <p>Alguém entrou em contato através do formulário de contato.</p>

        <p>Dados do contato:</p>
        <ul>
            <li><strong>Nome:</strong> {{ $name }}</li>
            <li><strong>E-mail:</strong> {{ $email }}</li>
            <li><strong>Assunto:</strong> {{ $messageSubject }}</li>
            <li><strong>Mensagem:</strong> {{ $contactMessage }}</li>
        </ul>

        <div class="footer">
            {{ config('app.name') }}
        </div>
    </div>
</body>

</html>
