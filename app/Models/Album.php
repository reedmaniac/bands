<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'albums';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'band_id',
        'name',
        'recorded_date',
        'release_date',
        'number_of_tracks',
        'producer',
        'genre'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'start_date',
        'recorded_date',
        'release_date'
    ];

    // ---------------------------------------------------------

    /**
     * Get Band for Album
     */
    public function band()
    {
        return $this->belongsTo(Band::class, 'band_id');
    }
}
