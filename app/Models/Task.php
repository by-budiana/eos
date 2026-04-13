<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'date',
        'status',
        'priority',
        'note',
        'deadline',
        'assigned_to',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
