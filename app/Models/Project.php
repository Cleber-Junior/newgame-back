<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model{

    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'current_value',
        'meta_value',
        'start_date',
        'end_date',
        'image',
        'id_creator',
    ];

    public function rules(){
        return [
            'name' => 'required',
            'status' => 'required',
            'id_creator' => 'required',
        ];
    }

    public function feedback(){
        return [
            'name.required' => 'O nome do projeto obrigatório',
            'status.required' => 'O campo status é obrigatório',
            'id_creator.required' => 'O campo criador é obrigatório',
        ];
    }

    public function creator(){
        return $this->belongsTo(User::class, 'id_creator');
    }
}
