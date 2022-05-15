<?php

namespace App\Exceptions;


use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        $title = $exception->getMessage();
        $errors = [];

        foreach ($exception->errors() as $field => $message) {
            $pointer = "/".str_replace('.', '/', $field);

            $errors[] = [
                'title' => $title,
                    'detail' => $message[0],
                    'source' => [
                        'pointer' => $pointer
                    ],
            ]; 
        }

        return response()->json([
            'errors' => $errors
        ], 422);
    }
}
