<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Signalement extends Model
{
    protected $table = 'signalements';
    
    protected $fillable = [
        'publication_id',
        'utilisateur_id',
        'type',
        'raison',
        'status',
        'action_taken',
        'moderated_by',
        'moderated_at',
    ];
    
    protected $dates = ['moderated_at'];
    
    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }
    
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
    
    public function moderator()
    {
        return $this->belongsTo(Utilisateur::class, 'moderated_by');
    }
}
