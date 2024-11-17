<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class RespException extends Exception
{
    protected int $statusCode;
    protected $message;

    /**
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;

        parent::__construct($message);
    }

    public function render(): RedirectResponse
    {
        return redirect()->back()->with('error', $this->message);
    }
}