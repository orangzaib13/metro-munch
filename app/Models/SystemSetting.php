<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $table = 'system_settings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'branch_id',
        'setting_key',
        'setting_value',
        'type',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'branch_id' => 'integer',
        'setting_value' => 'string',
    ];

    /**
     * Optional: Define relationship with Branch if you have a Branch model.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
