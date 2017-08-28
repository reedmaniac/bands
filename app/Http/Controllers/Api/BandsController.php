<?php

namespace App\Http\Controllers\Api;

use App\Libraries;
use Illuminate\Http\Request;

class BandsController extends Controller
{
    /**
     * The Library for managing this controller
     *
     * @var object
     */
    protected $bands;

    /**
     * The Request for this controller
     *
     * @var object
     */
    protected $request;

    /**
     * Any Middlweare we need?
     *
     *  @return void
     */
    public function __construct(Request $request, Libraries\Bands $bands)
    {
        parent::__construct($request);

        $this->bands = $bands;
    }

    // --------------------------------------------------------------------

    /**
     *  List Bands
     *
     *  @example curl -k -H "Accept: application/json" -H "Content-type: application/json" -X GET https://bands.app/api/bands
     *
     *  @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handle($this->bands->listBands($this->request->all()));
    }

    // --------------------------------------------------------------------

    /**
     * Post Band
     *
     * @example curl -H "Accept: application/json" -H "Content-type: application/json" -d '{"name":"Smashing Pumpkins"}' -X POST https://bands.app/api/bands
     *
     *  @return \Illuminate\Http\Response
     */
    public function store()
    {
        return $this->newResource($this->bands->createBand($this->request->all()));
    }

    // --------------------------------------------------------------------

    /**
     * Update Band
     *
     * @example curl -H "Accept: application/json" -H "Content-type: application/json" -d '{"name":"AC/DC"}' -X PUT https://bands.app/api/bands/1
     *
     *  @param  int|string  $url_title
     *  @return \Illuminate\Http\Response
     */
    public function update($band_id)
    {
        return $this->handle($this->bands->updateBand($band_id, $this->request->all()));
    }

    // --------------------------------------------------------------------

    /**
     * Show Single Band
     *
     * @example curl -H "Accept: application/json" -H "Content-type: application/json" -X GET https://bands.app/api/bands/1
     *
     *  @param  int $band_id
     *  @return \Illuminate\Http\Response
     */
    public function show($band_id)
    {
        return $this->handle($this->bands->getBand($band_id));
    }

    // --------------------------------------------------------------------

    /**
     * Delete Band
     *
     * @example curl -H "Accept: application/json" -H "Content-type: application/json" -X DELETE https://bands.app/api/bands/1
     *
     *  @param  int  $band_id
     *  @return \Illuminate\Http\Response
     */
    public function delete($band_id)
    {
        return $this->resourceDeleted($this->bands->deleteBand($band_id));
    }
}
