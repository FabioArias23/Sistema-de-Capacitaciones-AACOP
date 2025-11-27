<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'training_session_id',
        'status',
        'attendance',
        'grade',
    ];

    // Una inscripción pertenece a un usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
public function certificate(): HasOne
{
    return $this->hasOne(Certificate::class);
}
    // Una inscripción pertenece a una sesión de capacitación
    public function trainingSession(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class);
    }
}
