<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model{

    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_project',
        'date',
        'id_reward',
        'id_preference',
        'status',
        'value',
    ];

    public function creator(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function project(){
        return $this->belongsTo(Project::class, 'id_project');
    }

}
