<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todolist extends Model
{
    use HasFactory;
    protected $table = 'todolist';
    protected $primaryKey = 'id_todolist';
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
