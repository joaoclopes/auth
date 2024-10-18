<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $table = 'cadastro_form';

    protected $fillable = [
        'idInstituicao',
        'tipo',
        'tipoPessoa',
        'created_by',
        'updated_by',
    ];
}
