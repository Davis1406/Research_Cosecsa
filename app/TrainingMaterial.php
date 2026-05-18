<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Schedule;

class TrainingMaterial extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    public $table = 'training_materials';

    protected $appends = [
        'file',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'category',
        'type',
        'description',
        'external_url',
        'speaker_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function facilitator()
    {
        return $this->belongsTo(Speaker::class, 'speaker_id');
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_training_material');
    }

    public function getFileAttribute()
    {
        $file = $this->getMedia('file')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl();
        }

        return $file;
    }
}
