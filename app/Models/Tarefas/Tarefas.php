<?php

namespace App\Models\Tarefas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tarefas.tarefas';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @titulo string;
     * @descricao string;
     * @tag_id int;
     * @data date;
     * @hora time;
     * @tempo_duracao time;
     */
    protected $fillable = [
        'titulo',
        'descricao',
        'tag_id',
        'data',
        'hora',
        'tempo_duracao'
    ];

}
