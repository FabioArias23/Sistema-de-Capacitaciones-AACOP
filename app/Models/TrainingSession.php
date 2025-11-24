<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingSession extends Model
{
    use HasFactory;

    // No es necesario protected $table = 'training_sessions';

    protected $fillable = [
        'training_id',
        'campus_id',
        'training_title',
        'campus_name',
        'instructor',
        'date',
        'start_time',
        'end_time',
        'capacity',
        'status',
    ];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }
}
