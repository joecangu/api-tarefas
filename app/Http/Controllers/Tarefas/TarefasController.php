<?php

namespace App\Http\Controllers\Tarefas;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\Tarefas\Tarefas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarefasController extends Controller
{

    private $tarefas;

    public function __construct(Tarefas $tarefas)
    {
        $this->tarefas = $tarefas;
    }
    
    public function consultar()
    {

        try{

            $tarefas = DB::table('tarefas.tarefas')
                        ->join('tarefas.tags', 'tarefas.tarefas.tag_id', '=', 'tarefas.tags.id')
                        ->select('tarefas.tarefas.*', 'tarefas.tags.nome as tags')
                        ->orderBy('tarefas.tarefas.data')
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

   

    public function consultarTarefas(Request $request)
    {

        // PEGA OS VALORES DA REQUISIÇÃO
        $data = $request->all();

        // VARIAVEIS PARA CONSULTA
        $tag_id = $data['tag_id'];
        $titulo = $data['titulo'];
        $dataTarefa = $data['data'];
        $dataTarefaInicio = $data['data_tarefa_inicio'];
        $dataTarefaFinal = $data['data_tarefa_final'];
        $mes = $data['mes'];


        try{            

            $tarefas = DB::table('tarefas.tarefas')
                        ->join('tarefas.tags', 'tarefas.tarefas.tag_id', '=', 'tarefas.tags.id')
                        ->select('tarefas.tarefas.*', 'tarefas.tags.nome as tags')

                        // CONSULTA CASO TENHA TAG_ID
                        ->when($tag_id, function($tarefas) use($tag_id){
                            return $tarefas->whereIn('tarefas.tarefas.tag_id', $tag_id);
                        })

                        // CONSULTA CASO TENHA TITULO
                        ->when($titulo, function($tarefas) use($titulo){
                            return $tarefas->where('tarefas.tarefas.titulo','LIKE', '%'.$titulo.'%');
                        })

                        // CONSULTA CASO TENHA DATA
                        ->when($dataTarefa, function($tarefas) use($dataTarefa){
                            return $tarefas->where('tarefas.tarefas.data', '=', $dataTarefa);
                        })

                        // CONSULTA CASO TENHA SEMANA
                        ->when($dataTarefaInicio, function($tarefas) use($dataTarefaInicio, $dataTarefaFinal){
                            return $tarefas->whereBetween('tarefas.tarefas.data', [$dataTarefaInicio, $dataTarefaFinal]);
                        })

                        // CONSULTA CASO TENHA MES
                        ->when($mes, function($tarefas) use($mes){
                            return $tarefas->whereMonth('tarefas.tarefas.data', $mes);
                        })

                        ->orderBy('tarefas.tarefas.data')
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
        $tarefas = $this->tarefas->paginate('10');

        return response()->json($tarefas, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{

            $tarefas = $this->tarefas->findOrFail($id);

            return response()->json([
                'data' => $tarefas
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

            $tarefas = $this->tarefas->create($data);
            $tarefas = $this->consultar();

            return response()->json(
                [ 
                    'data' => $tarefas,
                    'msg' => 'Tarefa cadastrada com sucesso!'
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
            $tarefas = $this->tarefas->findOrFail($id);
            $tarefas->update($data);
            $tarefas = $this->consultar();

            return response()->json(
                [ 
                    'data' => $tarefas,
                    'msg' => 'Tarefa atualizada com sucesso!'
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

            $tarefas = $this->tarefas->findOrFail($id);
            $tarefas->delete();

            
            $tarefas = $this->consultar();

            return response()->json(
                [ 
                    'data' => $tarefas,
                    'msg' => 'Tarefa removida com sucesso!'
                ], 200
            );

        } catch (\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
