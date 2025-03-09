<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .firma-container { text-align: center; margin-top: 30px; }
        .firma { width: 200px; height: auto; }
    </style>
</head>
<body>
    <h1>Contrato de Trabajo</h1>
    <p>Este contrato certifica que <strong>{{ $usuario->nombre }}</strong> ha sido contratado en la empresa.</p>

    <div class="firma-container">
        <p>Firma del Usuario:</p>
        @if($usuario->firma)
            <img src="{{ route('usuarios.firma', ['path' => $usuario->id]) }}" class="firma" alt="Firma">
        @else
            <p>No se ha registrado firma.</p>
        @endif
    </div>

    <p>Fecha de ingreso: {{ $usuario->fecha_ingreso }}</p>
</body>
</html>
