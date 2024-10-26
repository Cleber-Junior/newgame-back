<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller{


    protected User $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function index(){
        $user = $this->user->all();
        return response()->json(['data' => $user], 200);
    }

    public function login(Request $request){
        try{
            $user = $this->user->where('email', $request->email)->first();
            if(!$user || !Hash::check($request->password, $user->password)){
                throw new Exception("Email ou senha inválidos");
            }

            $token = $user->createToken($user->email)->plainTextToken;
            return response()->json(['user' => $user, 'token' => $token], 200);
        } catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function register(Request $request){
        $request->validate($this->user->rules(), $this->user->feedback());
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $user = $this->user->create($data);
        return response()->json(['msg' => 'Usuário criado com sucesso', 'data' => $user], 201);
    }

    public function show($id){
        $user = $this->user->find($id);
        if(!$user){
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
        return response()->json(['msg' => 'Usuário encontrado', 'data' => $user], 200);
    }

    public function update(Request $request, $id){
        $user = $this->user->find($id);
        if(!$user){
            return response()->json(['error' => 'Usuário não encontrado. Impossivel executar a atualização'], 404);
        }

        if($request->method() === 'PATCH'){
            $dynamicRules = array();
            foreach($user->rules() as $input => $rules){
                if(array_key_exists($input, $request->all())){
                    $dynamicRules[$input] = $rules;
                }
            }
            $request->validate($dynamicRules, $user->feedback());
        } else {
            $request->validate($user->rules(), $user->feedback());
        }

        $user->update($request->all());
        return response()->json(['msg' => 'Usuário atualizado com sucesso', 'data' => $user], 200);
    }

    public function destroy($id){
        $user = $this->user->find($id);
        if(!$user){
            return response()->json(['error' => 'Usuário não encontrado. Impossivel executar a exclusão'], 404);
        }
        $user->delete();
        return response()->json(['msg' => 'Usuário deletado com sucesso'], 200);
    }
}
