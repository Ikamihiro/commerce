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
        <h1>Matrícula Cancelada</h1>

        <p>Olá, <strong>{{ $student->first_name }}</strong>!</p>

        <p>Sua matrícula no curso {{ $course->name }} foi cancelada.</p>
        <p>Caso tenha alguma dúvida, entre em contato conosco.</p>

        <p>Desde já, agradecemos a preferência.</p>

        <div class="footer">
            {{ config('app.name') }}
        </div>
    </div>
</body>

</html>
