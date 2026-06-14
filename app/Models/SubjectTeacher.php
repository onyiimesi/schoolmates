<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $term
 * @property string $session
 * @property int $class_id
 * @property string|null $class_name
 * @property int $staff_id
 * @property array<array-key, mixed> $subject
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ClassModel|null $class
 * @property-read array $resolved_subjects
 * @property-read \App\Models\Staff|null $staff
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubjectTeacherSubject> $subjects
 * @property-read int|null $subjects_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher whereClassName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacher whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'term',
    'session',
    'class_id',
    'staff_id',
    'class_name',
    'subject'
])]
class SubjectTeacher extends Model
{
    use HasFactory;

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
    protected function resolvedSubjects(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(get: function () {
            if ($this->subjects()->exists()) {
                return $this->subjects
                    ->map(fn($s) => ['name' => $s->subject_name])
                    ->all();
            }
            // Legacy fallback
            return $this->subject ?? [];
        });
    }
    protected function casts(): array
    {
        return [
            'subject' => 'array'
        ];
    }
}
