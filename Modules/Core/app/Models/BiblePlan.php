<?php

declare(strict_types=1);

namespace Modules\Bible\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiblePlan extends Model
{
    use SoftDeletes;

    protected $table = 'bible_plans';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'reading_mode',
        'duration_days',
        'cover_image',
        'allow_back_tracking',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'allow_back_tracking' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function days(): HasMany
    {
        return $this->hasMany(BiblePlanDay::class, 'plan_id')->orderBy('day_number');
    }
}
