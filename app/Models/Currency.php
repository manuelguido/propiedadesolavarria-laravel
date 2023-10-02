<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasFactory, SoftDeletes;

    const ARS = ['name' => 'Peso Argentino', 'short_name' => 'ARS', 'plural' => 'Pesos'];
    const USD = ['name' => 'Dólar', 'short_name' => 'USD', 'plural' => 'Dólares'];

    /**
     * Attributes
     */
    protected $table = 'currency';

    protected $primaryKey = 'currency_id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'short_name',
        'plural'
    ];

    /**
     * Get the Posts for the Currency.
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
