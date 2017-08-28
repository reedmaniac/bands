<?php

namespace App\Http\Controllers\Api;

use App\Libraries;
use Illuminate\Http\Request;

class AlbumsController extends Controller
{
    /**
     * The Library for managing this controller
     *
     * @var object
     */
    protected $albums;

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
    public function __construct(Request $request, Libraries\Albums $albums)
    {
        parent::__construct($request);

        $this->albums = $albums;
    }

    // --------------------------------------------------------------------

    /**
     *  List Albums
     *
     *  @example curl -k -H "Accept: application/json" -H "Content-type: application/json" -X GET https://bands.app/api/albums
     *
     *  @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handle($this->albums->listAlbums($this->request->all()));
    }

    // --------------------------------------------------------------------

    /**
     * Post Album
     *
     * @example curl -H "Accept: application/json" -H "Content-type: application/json" -d '{"name":"Smashing Pumpkins"}' -X POST https://bands.app/api/albums
     *
     *  @return \Illuminate\Http\Response
     */
    public function store()
    {
        return $this->newResource($this->albums->createAlbum($this->request->all()));
    }

    // --------------------------------------------------------------------

    /**
     * Update Album
     *
     * @example curl -H "Accept: application/json" -H "Content-type: application/json" -d '{"name":"AC/DC"}' -X PUT https://bands.app/api/albums/1
     *
     *  @param  int|string  $url_title
     *  @return \Illuminate\Http\Response
     */
    public function update($band_id)
    {
        return $this->handle($this->albums->updateAlbum($band_id, $this->request->all()));
    }

    // --------------------------------------------------------------------

    /**
     * Show Single Album
     *
     * @example curl -H "Accept: application/json" -H "Content-type: application/json" -X GET https://bands.app/api/albums/1
     *
     *  @param  int $band_id
     *  @return \Illuminate\Http\Response
     */
    public function show($band_id)
    {
        return $this->handle($this->albums->getAlbum($band_id));
    }

    // --------------------------------------------------------------------

    /**
     * Delete Album
     *
     * @example curl -H "Accept: application/json" -H "Content-type: application/json" -X DELETE https://bands.app/api/albums/1
     *
     *  @param  int  $band_id
     *  @return \Illuminate\Http\Response
     */
    public function delete($band_id)
    {
        return $this->resourceDeleted($this->albums->deleteAlbum($band_id));
    }
}
