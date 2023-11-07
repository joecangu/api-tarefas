<?php

namespace App\Models\Tarefas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tarefas.tags';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @nome string;
     */
    protected $fillable = [
        'nome',
    ];

}
