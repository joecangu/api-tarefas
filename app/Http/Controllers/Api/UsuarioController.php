<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    
    private $user;

    public function __construct(Usuario $user)
    {
        $this->user = $user;
    }

    public function consultar(Request $request)
    {
        try{

            $usuario = $request->get('usuario');
            $senha = $request->get('senha');

            $user = DB::table('acesso.usuario')
                        ->where('usuario', $usuario)
                        ->first();

            if ($user && Hash::check($senha, $user->senha)) {
                return response()->json(
                    $user, 
                    200
                );
            }
            
        } catch (\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }     
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->user->paginate('10');

        return response()->json($user, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{

            $user = $this->user->findOrFail($id);
            
            return response()->json([
                'data' => $user
            ], 200);

        } catch (\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function adicionar(Request $request)
    {
        $data = $request->all();

        $data['inclusao_data'] = Carbon::now();

        if(!$request->has('senha') || !$request->get('senha')){
            $message = new ApiMessages('É necessário informar uma senha para o usuário');
            return response()->json($message->getMessage(), 401);
        }

        try{

            $data['senha'] = bcrypt($data['senha']);

            $user = $this->user->create($data);

            return response()->json([
                'data' => [
                    'msg' => 'Usuário cadastrado com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    } 


    /**
     * Update the specified resource in storage.
     */
    public function alterar(Request $request, string $id)
    {
        $data = $request->all();

        $data['alteracao_data'] = Carbon::now();
        

        


        if($request->has('senha') && $request->get('senha')){
            $data['senha'] = bcrypt($data['senha']);
        } else {
            unset($data['senha']);
        }

        try{

            $user = $this->user->findOrFail($id);
            $user->update($data);

            return response()->json([
                'data' => [
                    'msg' => 'Usuário atualizado com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function excluir(string $id)
    {
        try{

            $user = $this->user->findOrFail($id);
            $user->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Usuário removido com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
