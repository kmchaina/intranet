<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBadge extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','badge_id','progress','level','awarded_at'];

    public function badge(): BelongsTo { return $this->belongsTo(Badge::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
