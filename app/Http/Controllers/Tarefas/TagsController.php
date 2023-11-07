<?php

namespace App\Http\Controllers\Tarefas;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\Tarefas\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
{

    private $tags;

    public function __construct(Tags $tags)
    {
        $this->tags = $tags;
    }
    
    public function consultar()
    {

        try{

            $tarefas = DB::table('tarefas.tags')
                        ->select('tarefas.tags.*')
                        ->orderBy('tarefas.tags.id')
                        ->get();
            
            return response()->json(['data' => 
                $tarefas], 
                200
            );
            
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
        $tags = $this->tags->paginate('10');

        return response()->json($tags, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{

            $tags = $this->tags->findOrFail($id);

            return response()->json([
                'data' => $tags
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


        try{

            $tags = $this->tags->create($data);
            $tags = $this->consultar();

            return response()->json(
                [ 
                    'data' => $tags,
                    'msg' => 'Tag cadastrada com sucesso!'
                ], 200
            );

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

        try{
            $tags = $this->tags->findOrFail($id);
            $tags->update($data);
            $tags = $this->consultar();

            return response()->json(
                [ 
                    'data' => $tags,
                    'msg' => 'Tag atualizada com sucesso!'
                ], 200
            );

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

            $tags = $this->tags->findOrFail($id);
            $tags->delete();

            
            $tags = $this->consultar();

            return response()->json(
                [ 
                    'data' => $tags,
                    'msg' => 'Tag removida com sucesso!'
                ], 200
            );

        } catch (\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
