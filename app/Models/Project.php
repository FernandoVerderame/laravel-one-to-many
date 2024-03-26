<?php

namespace App\Models;

use Illuminate\Support\Facades\Vite;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'description', 'is_completed', 'type_id'];

    public function getFormattedDate($column, $format = 'd-m-Y')
    {
        return Carbon::create($this->$column)->format($format);
    }

    public function printImage()
    {
        return Vite::asset('public/storage/' . $this->image);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    // Query Scope
    public function scopeCompleteFilter(Builder $query, $status)
    {
        if (!$status) return $query;
        $value = $status === 'completed';
        return $query->whereIsCompleted($value);
    }

    // Query Scope
    public function scopeTypeFilter(Builder $query, $type_id)
    {
        if (!$type_id) return $query;
        return $query->whereTypeId($type_id);
    }
}
