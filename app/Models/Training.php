<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'duration',
        'capacity',
        'level',
        'instructor',
        'status',
        'docente_id',   // 👈 NUEVO
    ];

    /**
     * Docente asignado a esta capacitación.
     */
    public function docente()
    {
        return $this->belongsTo(User::class, 'docente_id');
    }
}
