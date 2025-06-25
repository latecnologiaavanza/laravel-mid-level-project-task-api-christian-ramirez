<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Str;


class Task extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = ['title', 'project_id', 'description','status','priority','due_date'];

    protected static function booted()
    {
        static::creating(fn($model) => $model->id = (string) Str::uuid());
    }

    protected function project()
    {
        return $this->belongsTo(Project::class);
    }

}
