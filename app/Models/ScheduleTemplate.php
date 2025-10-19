<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'areas',
        'schedule_items',
        'is_active'
    ];

    protected $casts = [
        'areas' => 'array',
        'schedule_items' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Aktif şablonları getir
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Belirli bir alan için şablonları getir
     */
    public function scopeForArea($query, $area)
    {
        return $query->whereJsonContains('areas', $area);
    }

    /**
     * Şablonun ders sayısını getir
     */
    public function getItemsCountAttribute()
    {
        return count($this->schedule_items ?? []);
    }

    /**
     * Şablonun alanlarını string olarak getir
     */
    public function getAreasStringAttribute()
    {
        return implode(', ', $this->areas ?? []);
    }
}
