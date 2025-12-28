<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Modèle Role avec constantes et permissions JSON
class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = ['nom', 'slug', 'niveau', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    // Constantes de rôles (nommage en français)
    public const ETUDIANT = 'etudiant';
    public const MODERATEUR_GROUPE = 'moderateur_groupe';
    public const ADMIN_GROUPE = 'admin_groupe';
    public const MODERATEUR_GLOBAL = 'moderateur_global';
    public const ADMINISTRATEUR = 'administrateur';
    public const SUPER_ADMIN = 'super_admin';

    // Relation : utilisateurs associés à ce rôle
    public function utilisateurs(): HasMany
    {
        return $this->hasMany(Utilisateur::class, 'role_id');
    }
}
