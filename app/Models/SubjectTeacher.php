<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'term',
        'session',
        'class_id',
        'staff_id',
        'class_name',
        'subject'
    ];

    protected $casts = [
        'subject' => 'array'
    ];

    public function subjects(): HasMany
    {
        return $this->hasMany(SubjectTeacherSubject::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Resolves subjects from the relational table if populated,
     * otherwise falls back to the legacy JSON column.
     * Keeps backward compatibility for existing consumers.
     */
    public function getResolvedSubjectsAttribute(): array
    {
        if ($this->subjects()->exists()) {
            return $this->subjects
                ->map(fn($s) => ['name' => $s->subject_name])
                ->toArray();
        }

        // Legacy fallback
        return $this->subject ?? [];
    }
}
