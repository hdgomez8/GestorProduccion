<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompraEditRequest;
use App\Models\Compra;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class CompraController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('compras_index'), 403);
        $compras = Compra::where('MatQxAdq','like','%PENDIENTE%')->where(function ($query) {
            $query->where('ProReMaE','like','%S%')
                ->orWhere('ProDispEE','like','%S%');
        })->paginate(50);
        // dd($compras);
        return view('cirugias.compras.index', compact('compras'));
    }

    public function create()
    {
        // abort_if(Gate::denies('user_create'), 403);
        // $roles = Role::all()->pluck('name', 'id');
        // return view('users.create', compact('roles'));
    }

    public function store(UserCreateRequest $request)
    {
        // $request->validate([
        //     'name' => 'required|min:3|max:5',
        //     'username' => 'required',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required'
        // ]);
        // // $user = User::create($request->only('name', 'username', 'email')
        // //     + [
        // //         'password' => bcrypt($request->input('password')),
        // //     ]);

        // // $roles = $request->input('roles', []);
        // // $user->syncRoles($roles);
        // // return redirect()->route('users.show', $user->id)->with('success', 'Usuario creado correctamente');
    }

    public function show(Compra $compra)
    {
        // abort_if(Gate::denies('user_show'), 403);
        $compra = Compra::findOrFail($compra->ProCirCod);
        // // dd($user);
        // $user->load('roles');
        return view('cirugias.compras.show', compact('compra'));
    }

    public function edit(Compra $compra)
    {
        // abort_if(Gate::denies('user_edit'), 403);

        $compra = Compra::findOrFail($compra->ProCirCod);
        // $compras = \DB::table('dbo.ReporteTableroQX')
        // ->get();
        // $roles = Role::all()->pluck('name', 'id');
        // $user->load('roles');
        return view('cirugias.compras.edit', compact('compra'));
    }

    public function update(Request $request, Compra $compra)
    {
        $compra = Compra::findOrFail($compra->ProCirCod);
        $data = $request->only('MatQxAdq', 'ObsMatQx');
        // $compras = Compra::findOrFail($ProCirCod);
        // // $user=User::findOrFail($id);
        // $data = $request->only('name', 'username', 'email');
        // $password=$request->input('password');
        // if($password)
        //     $data['password'] = bcrypt($password);
        // // if(trim($request->password)=='')
        // // {
        // //     $data=$request->except('password');
        // // }
        // // else{
        // //     $data=$request->all();
        // //     $data['password']=bcrypt($request->password);
        // // }

        $compra->update($data);

        // $roles = $request->input('roles', []);
        // $user->syncRoles($roles);
        return redirect()->route('compras.index')->with('success', 'Compra Actualizada Correctamente');
    }

    public function destroy(User $user)
    {
        // abort_if(Gate::denies('user_destroy'), 403);

        // if (auth()->user()->id == $user->id) {
        //     return redirect()->route('users.index');
        // }

        // $user->delete();
        // return back()->with('succes', 'Usuario eliminado correctamente');
    }

    public function enTramite()
    {
        // abort_if(Gate::denies('compra_index'), 403);
        $compras = Compra::where("MatQxAdq", "like", '%EN TRAMITE%')
            // ->orwhere("MatQxAdq","like",'%EN TRAMITE%')
            ->paginate(200);
        // ->where("MatQxAdq","like","%COMPRADO%");
        // dd($compras);
        // ->paginate(5)
        return view('cirugias.compras.index', compact('compras'));
    }
    public function comprados()
    {
        // abort_if(Gate::denies('compra_index'), 403);
        $compras = Compra::where("MatQxAdq", "like", '%COMPRADO%')
            // ->orwhere("MatQxAdq","like",'%EN TRAMITE%')
            ->paginate(200);
        // ->where("MatQxAdq","like","%COMPRADO%");
        // dd($compras);
        // ->paginate(5)
        return view('cirugias.compras.index', compact('compras'));
    }
}
