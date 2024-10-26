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
        'id_creator',
    ];

    public function rules(){
        return [
            'name' => 'required',
            'status' => 'required',
            'meta_value' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'id_creator' => 'required',
        ];
    }

    public function feedback(){
        return [
            'name.required' => 'O campo nome é obrigatório',
            'status.required' => 'O campo status é obrigatório',
            'meta_value.required' => 'O campo valor meta é obrigatório',
            'start_date.required' => 'O campo data de início é obrigatório',
            'end_date.required' => 'O campo data de término é obrigatório',
            'id_creator.required' => 'O campo criador é obrigatório',
        ];
    }

    public function creator(){
        return $this->belongsTo(User::class, 'id_creator');
    }
}
