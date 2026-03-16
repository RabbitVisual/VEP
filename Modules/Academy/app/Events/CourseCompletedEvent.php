<?php

declare(strict_types=1);

namespace VertexSolutions\Academy\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use VertexSolutions\Academy\Models\Certificate;
use VertexSolutions\Academy\Models\Enrollment;

class CourseCompletedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Enrollment $enrollment,
        public Certificate $certificate
    ) {}
}
