<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionDaily extends Model
{
    use HasFactory;

    protected $table = 'adoption_daily';
    protected $primaryKey = 'date';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'date',
        'dau',
        'wau',
        'announcement_reads',
        'document_views',
        'document_downloads',
        'poll_views',
        'poll_responses',
        'vault_accesses',
        'vault_views',
        'new_user_activation'
    ];

    protected $casts = [
        'date' => 'date'
    ];
}
