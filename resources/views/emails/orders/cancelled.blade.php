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
            color: #3f3f3f;
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
        <h1>Pedido Cancelado</h1>

        <p>Olá, <strong>{{ $student->first_name }}</strong>!</p>

        <p>Seu pedido do curso {{ $course->name }} foi cancelado.</p>
        <p>Sua matrícula foi cancelada e o valor pago será estornado em breve.</p>

        <p>Desde já, agradecemos a preferência.</p>

        <div class="footer">
            {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
