<?php namespace App\Exceptions;

use Illuminate\Contracts\Validation\Validator as Validator;

class ValidationException extends \Exception {

    /**
     * Errors object.
     *
     * @var Laravel\Messages
     */
    private $errors;
    
    // --------------------------------------------------------------------

    /**
     *  Create a new validate exception instance.
     *
     *  @param  Laravel\Validator|Laravel\Messages  $container
     *  @return null
     */
    public function __construct($container)
    {
        parent::__construct(null);
        
        $this->errors = ($container instanceof Validator) ? $container->messages()->all() : $container;

        $this->message = (is_array($this->errors)) ? implode(',', $this->errors) : $this->errors;
    }

    /**
     *  Gets the error messages.
     *
     *  Typically, one would use the getMessages() function, but I added this just in case.
     *
     * @return Laravel\Messages
     */
    public function getErrors()
    {
        return $this->errors;
    }
}