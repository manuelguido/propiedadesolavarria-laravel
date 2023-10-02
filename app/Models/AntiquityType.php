<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AntiquityType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes
     */
    protected $table = 'antiquity_type';

    protected $primaryKey = 'antiquity_type_id';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Create a new antiquity Type.
     *
     * @return \App\Models\AntiquityType
     */
    public static function createantiquityType($antiquityTypeData): AntiquityType
    {
        $antiquityType = AntiquityType::create($antiquityTypeData);
        $antiquityType->save();
        return $antiquityType;
    }

    /**
     * Get the Properties for the AntiquityType.
     *
     * @return HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
