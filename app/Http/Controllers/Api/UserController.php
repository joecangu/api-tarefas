<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function consultar(Request $request)
    {

        try{

            $email = $request->get('email');
            $senha = $request->get('password');

            $user = DB::table('users')
                        ->where('email', $email)
                        ->first();

            if ($user && Hash::check($senha, $user->password)) {
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

            $user = $this->user->with('profile')->findOrFail($id);
            $user->profile->social_networks = unserialize($user->profile->social_networks);

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
    public function store(Request $request)
    {
        $data = $request->all();

        if(!$request->has('password') || !$request->get('password')){
            $message = new ApiMessages('É necessário informar uma senha para o usuário');
            return response()->json($message->getMessage(), 401);
        }

        try{

            $data['password'] = bcrypt($data['password']);

            $user = $this->user->create($data);

            return response()->json(
                [ 
                    'data' => $user,
                    'msg' => 'Usuário cadastrado com sucesso!'
                ], 200
            );

            // return response()->json([
            //     'msg' => 'Usuário cadastrado com sucesso!'
            // ], 200);

        } catch (\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    } 

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();

        if($request->has('password') && $request->get('password')){
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        try{
            $user = $this->user->findOrFail($id);
            $user->update($data);

            return response()->json([
                'msg' => 'Usuário atualizado com sucesso!'
            ], 200);

        } catch (\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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
