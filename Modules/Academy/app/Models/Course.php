<?php

namespace VertexSolutions\Academy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Course extends Model
{
    protected $table = 'academy_courses';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'level',
        'status',
    ];

    public const LEVEL_INICIANTE = 'iniciante';
    public const LEVEL_INTERMEDIARIO = 'intermediário';
    public const LEVEL_AVANCADO = 'avançado';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
                $original = $course->slug;
                $count = 0;
                while (static::where('slug', $course->slug)->exists()) {
                    $count++;
                    $course->slug = $original . '-' . $count;
                }
            }
        });
    }

    public function modules(): HasMany
    {
        return $this->hasMany(CourseModule::class, 'course_id')->orderBy('order');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'course_id');
    }

    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, CourseModule::class, 'course_id', 'module_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }
}
