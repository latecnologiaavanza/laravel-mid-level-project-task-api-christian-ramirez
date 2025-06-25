<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Str;

class Project extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = ['name', 'description', 'status'];

    protected static function booted()
    {
        static::creating(fn($model) => $model->id = (string) Str::uuid());
    }

    protected function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
