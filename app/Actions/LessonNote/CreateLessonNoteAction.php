<?php

declare(strict_types=1);

namespace App\Actions\LessonNote;

use App\DTOs\LessonNote\CreateLessonNoteDTO;
use App\Models\v2\LessonNote;

final readonly class CreateLessonNoteAction
{
    public function execute(CreateLessonNoteDTO $dto): void
    {
        LessonNote::create($dto->toArray());
    }
}
