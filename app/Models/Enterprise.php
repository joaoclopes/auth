<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    use HasFactory, HasUuids, Notifiable;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'can_activate_user',
        'personalized_messages',
        'multi_enterprise',
        'parent_id',
    ];

    public function headOffice()
    {
        return $this->belongsTo(Enterprise::class, 'parent_id');
    }

    public function branches()
    {
        return $this->hasMany(Enterprise::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'enterprise_id');
    }
}
