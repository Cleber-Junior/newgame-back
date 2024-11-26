<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rewards extends Model{

    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'value',
        'id_project',
    ];

    public function rules(){
        return [
            'name' => 'required',
            'description' => 'required',
            'value' => 'required',
            'id_project' => 'required',
        ];
    }

    public function feedback(){
        return [
            'name.required' => 'O nome da recompensa é obrigatório',
            'description.required' => 'A descrição da recompensa é obrigatória',
            'value.required' => 'O valor da recompensa é obrigatório',
            'id_project.required' => 'O projeto é obrigatório',
        ];
    }

    public function project(){
        return $this->belongsTo(Project::class, 'id_project');
    }

}
