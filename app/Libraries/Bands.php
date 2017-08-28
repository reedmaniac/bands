<?php

namespace App\Libraries;

use Log;
use Validator;
use Carbon\Carbon;
use App\Models;
use App\Exceptions\ValidationException;
use Illuminate\Validation\Rule;

class Bands
{
    // --------------------------------------------------------------------

    /**
     *  List Bands
     *
     *  @param  array   $incoming
     *  @return array
     */
    public function listBands(array $incoming = [])
    {
        $defaults = [
            'orderby'                   => 'name',
            'sort'                      => 'asc',
            'per_page'                  => 10,
            'page'                      => 1,
            'band_id:within'            => null
        ];

        // --------------------------------------------
        //  Incoming Overrides Defaults, Clear Out Empties
        // --------------------------------------------

        $incoming = array_filter(array_merge($defaults, $incoming));

        // --------------------------------------------
        //  Validation
        // --------------------------------------------

        $rules = [
            'orderby'   => 'required|in:name,start_date,website,still_active',
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

        $bands = new Models\Band;

        // --------------------------------------------
        //  WHERE Clause - Band ID Within List
        // --------------------------------------------

        if (!empty($incoming['band_id:within'])) {

            // Split
            if (!is_array($incoming['band_id:within'])) {
                $incoming['band_id:within'] = explode(',', $incoming['band_id:within']);
            }

            $bands = $bands->whereIn('id', $incoming['band_id:within']);
        }

        // --------------------------------------------
        //  Order By and Sort
        // --------------------------------------------

        $bands = $bands->orderBy($incoming['orderby'], $incoming['sort']);

        if ($incoming['orderby'] != 'name') {
            $bands = $bands->orderBy('name', $incoming['sort']);
        }

        // --------------------------------------------
        // Output
        // --------------------------------------------

        return $this->items($bands->get(), false);
    }

    // --------------------------------------------------------------------

    /**
     *  Create a Band
     *
     *  @param  array $incoming
     *  @return array
     */
    public function createBand(array $incoming)
    {
        // --------------------------------------------
        //  Validation
        // --------------------------------------------

        $validator = Validator::make($incoming, [
            'name'          => 'required|unique:bands,name',
            'start_date'    => 'nullable|date',
            'website'       => 'nullable|url',
            'still_active'  => 'boolean'
        ]);


        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->all());
        }

        // --------------------------------------------
        //  Create Band
        // --------------------------------------------

        $incoming['still_active'] =
            (isset($incoming['still_active'])) ?
            (bool) $incoming['still_active'] :
            false;

        $band = Models\Band::create($incoming);

        $band->save();

        // --------------------------------------------
        //  Return Band
        // --------------------------------------------

        return $this->item($band);
    }

    // --------------------------------------------------------------------

    /**
     *  Update a Band
     *
     *  @param  string $band_id
     *  @param  array $incoming
     *  @return array
     */
    public function updateBand($band_id, array $incoming)
    {
        // Exception handled by Exception Handler
        $band = Models\Band::findOrFail($band_id);

        $incoming = array_merge($band->toArray(), $incoming);

        // --------------------------------------------
        //  Validation
        // --------------------------------------------

        $validator = Validator::make($incoming, [
            'name'          => ['required', Rule::unique('bands', 'name')->ignore($band->id)],
            'start_date'    => 'nullable|date',
            'website'       => 'nullable|url',
            'still_active'  => 'boolean'
        ]);


        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->all());
        }

        // --------------------------------------------
        //  Update Band
        // --------------------------------------------

        $incoming['still_active'] =
            (isset($incoming['still_active'])) ?
            (bool) $incoming['still_active'] :
            false;

        $band->fill($incoming)->save();

        // --------------------------------------------
        //  Return Band
        // --------------------------------------------

        return $this->item($band->fresh());
    }

    // --------------------------------------------------------------------

    /**
     *  Delete a Band
     *
     *  @param  string $band_id
     *  @return void
     */
    public function deleteBand($band_id)
    {
        // --------------------------------------------
        //  Find Band
        // --------------------------------------------

        $band = Models\Band::findOrFail($band_id);

        $band->albums()->delete();

        $result = $band->delete();

        // --------------------------------------------
        //  Return Result
        // --------------------------------------------

        return $result;
    }

    // --------------------------------------------------------------------

    /**
     *  Get a Single Band
     *
     *  @param  string $band_id
     *  @return array
     */
    public function getBand($band_id)
    {
        return $this->item(Models\Band::with('albums')->findOrFail($band_id));
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
            'id'           => (int) $model->id,
            'name'         => (string) $model->name,
            'start_date'   => (is_null($model->start_date)) ? null : (string) $model->start_date->toDateString(),
            'website'      => (string) $model->website,
            'still_active' => (bool) $model->still_active,
            'created_at'   => (string) $model->created_at,
            'updated_at'   => (string) $model->updated_at,
        ];


        if ($model->relationLoaded('albums')) {
            $data['albums'] = null;

            if (!is_null($model->albums)) {
                $data['albums'] = Albums::items($model->albums);
            }
        }

        return $data;
    }

    // --------------------------------------------------------------------

    /**
     *  Multiple Albums Output
     *
     *  @param  Collection $collection
     *  @param boolean $children Load the relationships children or not
     *  @return array
     */
    public static function items($collection)
    {
        return array_map([Bands::class, 'item'], $collection->all());
    }

}