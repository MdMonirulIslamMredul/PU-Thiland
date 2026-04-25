<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'old_level',
        'new_level',
        'changed_by',
        'reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
