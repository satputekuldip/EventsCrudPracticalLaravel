<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Event
 * @package App\Models
 * @version April 19, 2021, 4:27 am UTC
 *
 * @property \App\Models\Category $category
 * @property \Illuminate\Database\Eloquent\Collection $images
 * @property string $name
 * @property string|\Carbon\Carbon $starts_at
 * @property string|\Carbon\Carbon $ends_at
 * @property string $type
 * @property string $banner
 * @property string $venue
 * @property integer $category_id
 * @property integer $price
 */
class Event extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'events';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'starts_at',
        'ends_at',
        'type',
        'banner',
        'venue',
        'category_id',
        'price'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'type' => 'string',
        'venue' => 'string',
        'category_id' => 'integer',
        'price' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'starts_at' => 'required',
        'ends_at' => 'required',
        'type' => 'required|string|in:PAID,FREE',
        'banner' => 'required|image',
        'venue' => 'required|string|max:255',
        'category_id' => 'required',
        'price' => 'integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function images()
    {
        return $this->hasMany(\App\Models\EventImage::class, 'event_id');
    }
}
