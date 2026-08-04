<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;



class Transaction extends Authenticatable
{

    protected $fillable = [
        'id_agent',
        'id_user',
        'type',
        'montant', 
        'numero_ex',
        'numero_des',
        'comission',
        'statut'
    ];

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'montant' => 'integer',
            'comission'=>'integer',
        ];
    }

    


// protected function comission(): Attribute
// {
//     return Attribute::make(
//         set: function (mixed $value, array $attributes) {
//             $type = $attributes['type'] ?? '';
//             $montant = $attributes['montant'] ?? 0;

//             if ($type === 'depot') {
//                 return $montant * 0.1;
//             }

//             if ($type === 'retrait') {
//                 return 100;
//             }

//             // return 0;
//         }
//     );
// }

protected static function booted(): void
{
    static::creating(function (Transaction $transaction) {
        if (empty($transaction->statut)) {
            $transaction->statut = 'initiée';
        }

        if ($transaction->type === 'depot') {
            $transaction->comission = $transaction->montant * 0.1;
        } elseif ($transaction->type === 'retrait') {
            $commissionActuelle = $transaction->comission ?? 0;
            $transaction->comission = $commissionActuelle + 100;
        } else {
            $transaction->comission = $transaction->comission ?? 0;
        }
    });
}


// protected function montant(): Attribute
// {
//     return Attribute::make(
//         set: function (mixed $value, array $attributes) {
//             $type = $attributes['type'] ?? '';
//             $montantSaisi = (int) $value;

//             if ($type === 'retrait') {
//                 return $montantSaisi + 100; 
//             }
//             if ($type === 'depot') {
//                 return $montantSaisi * 0.1 + $montantSaisi;
//             }

//             return $montantSaisi;
//         }
//     );
// }


    public function agents(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'id_agent');
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    
}
