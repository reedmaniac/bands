<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Controllers\Api\Controller AS ApiController;

trait RestApiExceptionHandler
{

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $e)
    {
        switch(true) {
            case $this->isBadCsrfTokenException($e):
                $retval = $this->badCsrfToken($e);
            break;
            case $this->isModelNotFoundException($e):
                $retval = $this->modelNotFound($e);
            break;
            case $this->isValidationException($e):
                $retval = $this->validationError($e);
                break;
            case $this->isApiFailureException($e):
                 $retval = $this->apiFailure($e);
                    break;
            default:
                $retval = $this->badRequest($e);
            break;
        }

        return $retval;
    }

    /**
     * Returns JSON response for bad CSRF Request
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badCsrfToken(Exception $e)
    {
        return ApiController::errorBadRequest('Invalid CSRF Token received for request.');
    }

    /**
     * Returns JSON response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest(Exception $e)
    {
        return ApiController::errorBadRequest($e->getMessage());
    }

    /**
     *  Returns JSON response for Unauthorized Access.
     *  - Either they do not have permission
     *  - Or, they tried to login and failed
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthorizedAccess(Exception $e)
    {
        return ApiController::errorUnauthorized($e->getMessage());
    }

    /**
     * Returns JSON response for Eloquent model not found exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function modelNotFound(Exception $e)
    {
        return ApiController::errorNotFound($e->getMessage());
    }

    /**
     * Returns JSON response for Validation Not Found Exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationError(Exception $e)
    {
        return ApiController::errorInvalidArguments($e->getErrors());
    }

    /**
     * Returns JSON response for API Failure Exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function apiFailure(Exception $e)
    {
        return ApiController::errorInternalError($e->getMessage());
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    /**
     * Determines if the given exception is a Validation Exception
     *
     * @param Exception $e
     * @return bool
     */
    protected function isValidationException(Exception $e)
    {
        return $e instanceof \App\Exceptions\ValidationException;
    }

    /**
     * Determines if the given exception is a Validation Exception
     *
     * @param Exception $e
     * @return bool
     */
    protected function isBadMethodCallException(Exception $e)
    {
        return $e instanceof BadMethodCallException;
    }

    /**
     * Determines if the given exception is a Unauthorized Exception
     *
     * @param Exception $e
     * @return bool
     */
    protected function isUnauthorizedException(Exception $e)
    {
        return $e instanceof \App\Exceptions\UnauthorizedException;
    }

    /**
     * Determines if the given exception is a API Exception
     *
     * @param Exception $e
     * @return bool
     */
    protected function isApiFailureException(Exception $e)
    {
        return $e instanceof \App\Exceptions\ApiFailureException;
    }

    /**
     * Determines if the given exception is a API Exception
     *
     * @param Exception $e
     * @return bool
     */
    protected function isBadCsrfTokenException(Exception $e)
    {
        return $e instanceof \Illuminate\Session\TokenMismatchException;
    }
}