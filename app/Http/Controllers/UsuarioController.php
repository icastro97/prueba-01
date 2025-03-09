<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('role')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo_electronico' => 'required|email|unique:usuarios,correo_electronico',
            'id_rol' => 'required|exists:roles,id',
            'fecha_ingreso' => 'required|date',
            'firma' => 'required',
        ]);

        $usuario = User::create($request->except('firma'));

        if ($request->firma) {
            $firmaBase64 = str_replace('data:image/png;base64,', '', $request->firma);
            $firmaBase64 = str_replace(' ', '+', $firmaBase64);
            $firmaData = base64_decode($firmaBase64);
            $firmaPath = "firmas/firma_usuario_{$usuario->id}.png";

            Storage::put("public/$firmaPath", $firmaData);

            $usuario->update(['firma' => $firmaPath]);
        }

        $usuario->refresh();

        $pdf = Pdf::loadView('usuarios.contrato', compact('usuario'));
        $contratoPath = "contratos/usuario_{$usuario->id}.pdf";
        Storage::put("public/$contratoPath", $pdf->output());

        $usuario->update(['contrato' => $contratoPath]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado correctamente.');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo_electronico' => 'required|email|unique:users,correo_electronico,' . $id,
            'id_rol' => 'required|exists:roles,id',
            'fecha_ingreso' => 'required|date',
            'firma' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $usuario = User::findOrFail($id);
            $usuario->update([
                'nombre' => $request->nombre,
                'correo_electronico' => $request->correo_electronico,
                'id_rol' => $request->id_rol,
                'fecha_ingreso' => $request->fecha_ingreso,
            ]);

            if ($request->hasFile('firma')) {
                if ($usuario->firma) {
                    Storage::delete('public/' . $usuario->firma);
                }
                $firmaBase64 = str_replace('data:image/png;base64,', '', $request->firma);
                $firmaBase64 = str_replace(' ', '+', $firmaBase64);
                $firmaData = base64_decode($firmaBase64);
                $firmaPath = "firmas/firma_usuario_{$usuario->id}.png";

                Storage::put("public/$firmaPath", $firmaData);

                $usuario->firma = $firmaPath;
            }

            $pdf = Pdf::loadView('usuarios.contrato', compact('usuario'));
            $contratoPath = "contratos/usuario_{$usuario->id}.pdf";
            Storage::put("public/$contratoPath", $pdf->output());

            $usuario->contrato = $contratoPath;
            $usuario->save();

            DB::commit();
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error al actualizar usuario: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $usuario = User::findOrFail($id);

            if ($usuario->contrato) {
                Storage::delete('public/' . $usuario->contrato);
            }
            if ($usuario->firma) {
                Storage::delete('public/' . $usuario->firma);
            }

            $usuario->delete();
            DB::commit();

            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error al eliminar usuario: ' . $e->getMessage());
        }
    }

    public function getContract($id)
    {
        $user = User::find($id);
        $path = $user->contrato;

        if (!Storage::exists("public/$path")) {
            abort(404, 'El contrato no existe.');
        }

        $file = Storage::get("public/$path");
        $mimeType = Storage::mimeType("public/$path");

        return response($file, 200)->header('Content-Type', $mimeType);
    }

    public function getFirma($path)
    {
        $usuario = User::find($path);
        $ruta = $usuario->firma;
        if (!Storage::exists("public/$ruta")) {
            abort(404, 'Firma no encontrada.');
        }

        $file = Storage::get("public/$ruta");
        $mimeType = Storage::mimeType("public/$ruta");

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
