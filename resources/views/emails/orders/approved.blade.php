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
        <h1>Pedido Aprovado</h1>

        <p>Olá, <strong>{{ $student->first_name }}</strong>!</p>

        <p>Seu pedido do curso <strong>{{ $course->name }}</strong> foi aprovado.</p>
        <p>Sua matrícula está sendo processada e em breve você receberá um e-mail com as informações de acesso.</p>

        <p>Desde já, agradecemos a preferência.</p>

        <div class="footer">
            {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
