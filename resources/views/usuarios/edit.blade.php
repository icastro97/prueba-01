@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Usuario</h2>
    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $usuario->nombre }}" required>
        </div>

        <div class="mb-3">
            <label for="correo_electronico" class="form-label">Correo Electrónico</label>
            <input type="email" name="correo_electronico" class="form-control" value="{{ $usuario->correo_electronico }}" required>
        </div>

        <div class="mb-3">
            <label for="id_rol" class="form-label">Cargo</label>
            <select name="id_rol" class="form-control" required>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}" {{ $usuario->id_rol == $rol->id ? 'selected' : '' }}>{{ $rol->nombre_cargo }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
            <input type="date" name="fecha_ingreso" class="form-control" value="{{ $usuario->fecha_ingreso }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Firma</label>
            <div class="signature-container">
                <canvas id="signature-pad" class="signature-pad" style="border: 1px solid #000; width: 100%; height: 200px;"></canvas>
                <button type="button" id="clear-signature" class="btn btn-danger btn-sm mt-2">Limpiar Firma</button>
            </div>
            <input type="hidden" name="firma" id="firma_input">
            @if($usuario->firma)
                <p>Firma actual:</p>
                <img src="{{ route('usuarios.firma', ['path' => $usuario->id]) }}" alt="Firma" style="width: 200px; height: auto;">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<!-- Signature Pad JS -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const canvas = document.getElementById("signature-pad");
        const signaturePad = new SignaturePad(canvas);
        const clearButton = document.getElementById("clear-signature");
        const firmaInput = document.getElementById("firma_input");

        clearButton.addEventListener("click", function () {
            signaturePad.clear();
        });

        document.querySelector("form").addEventListener("submit", function () {
            if (!signaturePad.isEmpty()) {
                firmaInput.value = signaturePad.toDataURL();
            }
        });
    });
</script>
@endsection
