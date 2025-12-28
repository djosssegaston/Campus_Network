<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

// Modèle Reaction polymorphique
class Reaction extends Model
{
    use HasFactory;

    protected $table = 'reactions';

    protected $fillable = ['utilisateur_id', 'reactable_id', 'reactable_type', 'type'];

    // Utilisateur qui a réagi
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    // Relation polymorphique vers le contenu réagi
    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }
}
