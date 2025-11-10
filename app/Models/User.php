<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    // APONTAR PARA A TABELA 'usuarios'
    protected $table = 'usuarios';

    protected $fillable = [
        'nome_completo',
        'email',
        'password',
        'tipo_usuario',
        'crm',
        'telefone',
        'ativo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verificado_em' => 'datetime',
            'password' => 'hashed',
            'ativo' => 'boolean',
        ];
    }

    // Accessor para o nome (Auth usa 'name' por padrão)
    public function getNameAttribute()
    {
        return $this->nome_completo;
    }
}
