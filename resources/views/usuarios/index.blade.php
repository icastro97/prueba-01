@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Gestión de Usuarios</h2>
    <a href="{{ route('usuarios.create') }}" class="btn btn-success mb-3">Registrar Usuario</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo Electrónico</th>
                <th>Cargo</th>
                <th>Fecha de Ingreso</th>
                <th>Días Trabajados</th>
                <th>Contrato</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->id }}</td>
                <td>{{ $usuario->nombre }}</td>
                <td>{{ $usuario->correo_electronico }}</td>
                <td>{{ $usuario->role->nombre_cargo }}</td>
                <td>{{ $usuario->fecha_ingreso }}</td>
                <td>{{ calcularDiasHabiles($usuario->fecha_ingreso) }}</td>
                <td>
                    @if($usuario->contrato)
                        <a href="{{route('get.contract', $usuario->id)}}" target="_blank" class="btn btn-info">Ver Contrato</a>
                    @endif
                </td>
                <td>
                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
