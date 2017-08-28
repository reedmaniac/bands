<?php namespace App\Http\Controllers\Api;

use App\BaseRepository;
use App\Http\Controllers\Controller as BaseController;
use App\Traits\TestApi;

use Log;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    /**
     * The Request for this controller
     *
     * @var object
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // --------------------------------------------------------------------

    /**
     *  Handle Response
     *
     *  Currently, we only output JSON. Who knows, we may change based off HTTP_ACCEPT
     *
     *  @access protected
     *  @param  array|string  $data
     *  @return \Illuminate\Http\Response
     */
    protected function handle($data, $code = 200)
    {
        if (!is_null($data))  {
            $data = ['data' => $data];
        }

        return response()->json($data, $code);
    }

    // --------------------------------------------------------------------

    /**
     *  New Resource - Handle Response
     *
     *  @access protected
     *  @param  array  $data
     *  @return \Illuminate\Http\Response
     */
    protected function newResource($data)
    {
        return $this->handle($data, 201);
    }

    // --------------------------------------------------------------------

    /**
     *  Resource Deleted Response
     *
     *  @access     protected
     *  @param      array|bool
     *  @return     Response
     */

    protected function resourceDeleted($data)
    {
        return $this->handle(null, 204);
    }

    // --------------------------------------------------------------------

    /**
     *  Request Handled - 205 Reset Content
     *
     *  @access     protected
     *  @return     Response
     */

    protected function requestHandled()
    {
        return $this->handle(null, 205);
    }

    // --------------------------------------------------------------------

    /**
     *  Send Error
     *
     *  @access     public
     *  @param      string          $code - Unique, not generic, not translated
     *  @param      string          $message - Simple, one line message
     *  @param      string|array    $details - More details of issue, array if multiple issues
     *  @param      string|integer  $httpCode - The HTTP Code to send for this error, usually 404, API errors get 50x
     *  @return     Response
     */

    public static function sendError($code = 'error', $message = 'Error', $details = '', $httpCode = 404)
    {
        if ($httpCode === 200) {
            trigger_error(
                "You better have a really good reason for erroring on a 200...",
                E_USER_WARNING
            );
        }

        return response()->json(
            [
                'code'          => $code,
                'message'       => $message,
                'details'       => $details
            ],
            $httpCode);
    }

    // --------------------------------------------------------------------

    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @return  Response
     */
    public static function errorForbidden($details = '', $message = 'Forbidden')
    {
        return static::sendError('forbidden', $message, $details, 403);
    }

    // --------------------------------------------------------------------

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @return  Response
     */
    public static function errorInternalError($details = '', $message = 'We encountered a problem while processing request.')
    {
        Log::error($details);

        return static::sendError('internal_error', $message, $details, 500);
    }

    // --------------------------------------------------------------------

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @return  Response
     */
    public static function errorNotFound($details = '', $message = 'Resource Not Found')
    {
        return static::sendError('resource_not_found', $message, $details, 404);
    }

    // --------------------------------------------------------------------

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @return  Response
     */
    public static function errorUnauthorized($details = '', $message = 'Unauthorized')
    {
        return static::sendError('unauthorized', $message, $details, 401);
    }

    // --------------------------------------------------------------------

    /**
     *  Generates a Response with a 400 HTTP header and a given message.
     *  - Typically used for Validation Errors
     *
     * @return  Response
     */
    public static function errorInvalidArguments($details = '', $message = 'Invalid Arguments')
    {
        return static::sendError('invalid_arguments', $message, $details, 400);
    }

    // --------------------------------------------------------------------

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @return  Response
     */
    public static function errorBadRequest($details = '', $message = 'Bad Request')
    {
        return static::sendError('bad_request', $message, $details, 400);
    }
}
