<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Band extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'start_date',
        'website',
        'still_active'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'start_date'
    ];

    // ---------------------------------------------------------

    /**
     * Get Albums for Band
     */
    public function albums()
    {
        return $this->hasMany(Album::class, 'band_id', 'id');
    }
}
