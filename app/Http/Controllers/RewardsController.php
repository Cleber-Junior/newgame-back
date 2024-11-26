<?php

namespace App\Http\Controllers;

use App\Models\Rewards;
use Illuminate\Http\Request;

class RewardsController extends Controller{

    protected Rewards $rewards;
    public function __construct(Rewards $rewards){
        $this->rewards = $rewards;
    }

    public function index(){
        //
    }

    public function store(Request $request){
        $request->validate($this->rewards->rules(), $this->rewards->feedback());

        $rewards = $this->rewards->create($request->all());

        return response()->json(['msg' => 'Recompensa criada com sucesso', 'reward' => $rewards], 201);
    }

    public function allByProject($id){
        $data = $this->rewards->where('id_project', $id)->get();
        if($data === null){
            return response()->json(['error' => 'Nenhuma recompensa encontrada'], 404);
        }

        return response()->json(['rewards' => $data], 200);
    }

    public function show(Rewards $rewards){
        //
    }

    public function update(Request $request, $id){
        $rewards = $this->rewards->find($id);
        if(!$rewards){
            return response()->json(['error' => 'Recompensa não encontrada'], 404);
        }

        $request->validate($this->rewards->rules(), $this->rewards->feedback());
        $rewards->update($request->all());

        return response()->json(['msg' => 'Recompensa atualizada com sucesso', 'reward' => $rewards], 200);

    }

    public function destroy($id){
        $rewards = $this->rewards->find($id);
        if(!$rewards){
            return response()->json(['error' => 'Recompensa não encontrada'], 404);
        }

        $rewards->delete();
        return response()->json(['msg' => 'Recompensa deletada com sucesso'], 200);
    }
}
