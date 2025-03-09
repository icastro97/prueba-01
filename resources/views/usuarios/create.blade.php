@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Usuario</h2>
    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="correo_electronico" class="form-label">Correo Electrónico</label>
            <input type="email" name="correo_electronico" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="id_rol" class="form-label">Cargo</label>
            <select name="id_rol" class="form-control">
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}">{{ $rol->nombre_cargo }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
            <input type="date" name="fecha_ingreso" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Firma Digital</label>
            <div class="border p-2">
                <canvas id="signature-pad" width="400" height="200" style="border:1px solid #000;"></canvas>
            </div>
            <button type="button" class="btn btn-secondary mt-2" id="clear-signature">Borrar Firma</button>
        </div>

        <input type="hidden" name="firma" id="firma">

        <button type="submit" class="btn btn-primary" onclick="guardarFirma()">Registrar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let canvas = document.getElementById("signature-pad");
        let signaturePad = new SignaturePad(canvas);

        document.getElementById("clear-signature").addEventListener("click", function() {
            signaturePad.clear();
        });

        window.guardarFirma = function() {
            if (!signaturePad.isEmpty()) {
                document.getElementById("firma").value = signaturePad.toDataURL("image/png");
            }
        };
    });
</script>
@endsection
