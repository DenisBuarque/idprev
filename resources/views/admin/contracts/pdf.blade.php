<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style type="text/css">
        * {
            font-family: Arial, Helvetica, sans-serif;
        }
        h3, label {
            text-align: center;
            display: block;
        }
    </style>
</head>

<body>
    <h3>Modelo de Contrato de Assessor</h3>
    <label>{{$contract->process->title}}</label>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ab neque voluptatibus optio, itaque id dicta quae? Id optio rerum commodi, possimus similique debitis obcaecati illo placeat atque quasi in qui.</p>
    <p>Assessor(es):</p>
    @foreach ($contract->advisors as $adv)
        <span>{{ $adv->name }} OAB {{$adv->oab}}</span><br />
    @endforeach
    <br/>
    <p>Observação: {{ $contract->description }}</p>
</body>

</html>
