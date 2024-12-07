<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Build;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id', 
        'build_id', 
        'activity_timestamp', 
        'action', 
        'type', 
        'activity', 
        'activity_details', 
        'created_at', 
        'updated_at'
    ];

    protected $dates = ['activity_timestamp', 'created_at', 'updated_at'];

    public function getActivityTimestampAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function build(): BelongsTo
    {
        return $this->belongsTo(Build::class);
    }
}
