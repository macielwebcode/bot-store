<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
    <h1>OLÁ, ESTE É UM EMAIL TESTE do {{ env('APP_NAME', "Projeto") }} </h1>
    <br>
    <br>
    <br>

    <strong> Para entrar, use a API através do endpoint:<br>
    {{ env("APP_URL")}}
</body>
</html>