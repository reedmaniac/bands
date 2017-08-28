<?php

namespace App\Libraries;

use Log;
use Validator;
use App\Models;
use Carbon\Carbon;
use App\Exceptions\ValidationException;

class Albums
{
    static $genres = [
        'Blues',
        'Comedy',
        'Children’s Music',
        'Classical',
        'Country',
        'Electronic',
        'Holiday',
        'Opera',
        'Jazz',
        'Latino',
        'New Age',
        'Pop',
        'R&B/Soul',
        'Soundtrack',
        'Dance',
        'Hip-Hop',
        'Rap',
        'World',
        'Alternative',
        'Rock',
        'Christian & Gospel',
        'Reggae'
    ];


    // --------------------------------------------------------------------

    /**
     *  List Albums
     *
     *  @param  array   $incoming
     *  @return array
     */
    public function listAlbums(array $incoming = [])
    {
        $defaults = [
            'orderby'                   => 'name',
            'sort'                      => 'asc',
            'per_page'                  => 10,
            'page'                      => 1,
            'band_id:equals'            => null,
            'band_id:within'            => null
        ];

        // --------------------------------------------
        //  Defaults
        // --------------------------------------------

        $incoming = array_filter(array_merge($defaults, $incoming));

        // --------------------------------------------
        //  Validation
        // --------------------------------------------

        $rules = [
            'orderby'   => 'required|in:name,release_date,recorded_date,number_of_tracks,producer,genre,band',
            'sort'      => 'required|in:asc,desc',
            'page'      => 'required|min:1|max:9999',
            'per_page'  => 'required|min:1|max:100'
        ];

        $validator = Validator::make($incoming, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->all());
        }

        // --------------------------------------------
        //  The Base of Query
        // --------------------------------------------

        $albums = Models\Album::with('band')
            ->select('albums.*');

        // --------------------------------------------
        //  WHERE Clause - Band ID Equals
        // --------------------------------------------

        if (!empty($incoming['band_id:equals'])) {
            $albums = $albums->where('band_id', $incoming['band_id:equals']);
        }

        // --------------------------------------------
        //  WHERE Clause - Band ID Within List
        // --------------------------------------------

        if (!empty($incoming['band_id:within'])) {

            // Split
            if (!is_array($incoming['band_id:within'])) {
                $incoming['band_id:within'] = explode(',', $incoming['band_id:within']);
            }

            $albums = $albums->whereIn('band_id', $incoming['band_id:within']);
        }

        // --------------------------------------------
        //  Order By and Sort
        // --------------------------------------------

        if ($incoming['orderby'] == 'band') {
            $albums->join('bands', 'bands.id', '=', 'albums.band_id')
                ->orderBy('bands.name', $incoming['sort']);
        } else {
            $albums = $albums->orderBy('albums.'.$incoming['orderby'], $incoming['sort']);
        }

        if ($incoming['orderby'] != 'name') {
            $albums = $albums->orderBy('albums.name', $incoming['sort']);
        }

        // --------------------------------------------
        // Output
        // --------------------------------------------

        return $this->items($albums->get());
    }

    // --------------------------------------------------------------------

    /**
     *  Create a Album
     *
     *  @param  array $incoming
     *  @return array
     */
    public function createAlbum(array $incoming)
    {
        // --------------------------------------------
        //  Validation
        // --------------------------------------------

        $validator = Validator::make($incoming, [
            'band_id'           => 'required|exists:bands,id',
            'name'              => 'required', // not unique
            'recorded_date'     => 'nullable|date',
            'release_date'      => 'nullable|date',
            'number_of_tracks'  => 'nullable|integer|min:1',
            'genre'             => 'nullable|in:'.implode(',', static::$genres)
        ]);


        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->all());
        }

        // --------------------------------------------
        //  Create Album
        // --------------------------------------------

        $album = Models\Album::create($incoming);

        $album->save();

        // --------------------------------------------
        //  Return Album
        // --------------------------------------------

        return $this->item($album);
    }

    // --------------------------------------------------------------------

    /**
     *  Update a Album
     *
     *  @param  string $album_id
     *  @param  array $incoming
     *  @return array
     */
    public function updateAlbum($album_id, array $incoming)
    {
        // Exception handled by Exception Handler
        $album = Models\Album::findOrFail($album_id);

        $incoming = array_merge($album->toArray(), $incoming);

        // --------------------------------------------
        //  Validation
        // --------------------------------------------

        $validator = Validator::make($incoming, [
            'band_id'           => 'required|exists:bands,id',
            'name'              => 'required', // not unique
            'recorded_date'     => 'nullable|date',
            'release_date'      => 'nullable|date',
            'number_of_tracks'  => 'nullable|integer|min:1',
            'genre'             => 'nullable|in:'.implode(',', static::$genres)
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->all());
        }

        // --------------------------------------------
        //  Update Album
        // --------------------------------------------

        $album->fill($incoming)->save();

        // --------------------------------------------
        //  Return Album
        // --------------------------------------------

        return $this->item($album->fresh());
    }

    // --------------------------------------------------------------------

    /**
     *  Delete a Album
     *
     *  @param  string $album_id
     *  @return void
     */
    public function deleteAlbum($album_id)
    {
        // --------------------------------------------
        //  Find Album
        // --------------------------------------------

        $album = Models\Album::findOrFail($album_id);

        $result = $album->delete();

        // --------------------------------------------
        //  Return Result
        // --------------------------------------------

        return $result;
    }

    // --------------------------------------------------------------------

    /**
     *  Get a Single Album
     *
     *  @param  string $album_id
     *  @return array
     */
    public function getAlbum($album_id)
    {
        return $this->item(Models\Album::with('band')->findOrFail($album_id));
    }

    // --------------------------------------------------------------------

    /**
     *  Get a Single Band
     *
     *  @param  string $album_id
     *  @return array
     */
    public function getBand($album_id)
    {
        return $this->item(Models\Band::findOrFail($album_id));
    }

    // --------------------------------------------------------------------

    /**
     *  Format Band Output
     *
     *  @param  App\Models\Band $model
     *  @param boolean $children Load the relationships children or not
     *  @return array
     */
    public static function item($model)
    {
        $data = [
            'id'               => (int) $model->id,
            'name'             => (string) $model->name,
            'band_id'          => (integer) $model->band_id,
            'recorded_date'    => (is_null($model->recorded_date)) ? null : (string) $model->recorded_date->toDateString(),
            'release_date'     => (is_null($model->release_date)) ? null : (string) $model->release_date->toDateString(),
            'number_of_tracks' => (is_null($model->number_of_tracks)) ? null : (integer) $model->number_of_tracks,
            'producer'         => (is_null($model->producer)) ? null : (string) $model->producer,
            'genre'            => (is_null($model->genre)) ? null : (string) $model->genre,
            'created_at'       => (string) $model->created_at,
            'updated_at'       => (string) $model->updated_at,
        ];

        if ($model->relationLoaded('band')) {
            $data['band'] = null;

            if (!is_null($model->band)) {
                $data['band'] = Bands::item($model->band, false);
            }
        }

        return $data;
    }

    // --------------------------------------------------------------------

    /**
     *  Multiple Bands Output
     *
     *  @param  Collection $collection
     *  @param boolean $children Load the relationships children or not
     *  @return array
     */
    public static function items($collection)
    {
        return array_map([Albums::class, 'item'], $collection->all());
    }
}