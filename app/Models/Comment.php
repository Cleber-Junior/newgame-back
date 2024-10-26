<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model{

    use HasFactory;

    protected $fillable = [
        'comment',
        'date_time',
        'id_project',
        'id_user',
    ];

    public function project(){
        return $this->belongsTo(Project::class, 'id_project');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }
}
