<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    protected Project $project;
    public function __construct(Project $project){
        $this->project = $project;
    }

    public function index(){
        $project = $this->project->all();
        return response()->json(['data' => $project], 200);
    }

    public function show($id){
        $project = $this->project->find($id);
        if(!$project){
            return response()->json(['error' => 'Projeto não encontrado'], 404);
        }

        return response()->json(['msg' => 'Projeto encontrado', 'data' => $project], 200);
    }

    public function store(Request $request){
        $startData = Carbon::now();

        $request['current_value'] = 0;
        $request['status'] = false;
        $request['start_date'] = $startData;

        $request->validate($this->project->rules(), $this->project->feedback());

        $project = $this->project->create($request->all());

        return response()->json(['msg' => 'Projeto criado com sucesso', 'data' => $project], 201);
    }

    public function update(Request $request, $id){
        $project = $this->project->find($id);
        if(!$project){
            return response()->json(['error' => 'Projeto não encontrado. Impossivel executar a atualização'], 404);
        }

        if($request->method() === 'PATCH'){
            $dinamicRules = array();
            foreach($project->rules() as $input => $rule){
                if(array_key_exists($input, $request->all())){
                    $dinamicRules[$input] = $rule;
                }
            }

            $request->validate($dinamicRules, $project->feedback());
        } else {
            $request->validate($project->rules(), $project->feedback());
        }

        $project->update($request->all());
        return response()->json(['msg' => 'Projeto atualizado com sucesso', 'data' => $project], 200);
    }

    public function destroy($id){
        $project = $this->project->find($id);
        if(!$project){
            return response()->json(['error' => 'Projeto não encontrado. Impossivel executar a exclusão'], 404);
        }

        $project->delete();
        return response()->json(['msg' => 'Projeto excluído com sucesso'], 200);
    }
}
