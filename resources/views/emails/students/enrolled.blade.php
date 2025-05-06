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
        <p>Prezado(a) <strong>{{ $student->first_name }}</strong></p>

        <p>Seja bem vindo ao Curso <strong>{{ $course->name }}</strong></p>

        <p>Acesse a sua área do curso.</p>

        <p>Dados para acessar:</p>
        <ul>
            <li><strong>Site de acesso:</strong> <a href="https://salavirtual.feics.com.br">https://salavirtual.feics.com.br</a></li>
            <li><strong>E-mail:</strong> {{ $student->email }}</li>
            <li><strong>Senha:</strong> {{ $student->password }}</li>
        </ul>

        <p>Estamos esperando você no Curso <strong>{{ $course->name }}</strong>..</p>
        <p>Um grande abraço.</p>

        <div class="footer">
            {{ config('app.name') }}
        </div>
    </div>
</body>

</html>
