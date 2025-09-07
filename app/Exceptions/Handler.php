<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        if (method_exists($exception, 'getStatusCode') && $exception->getStatusCode() === 419) {
            // Pass error details to the 419 view
            return response()->view('errors.419', [
                'error' => $exception->getMessage(),
                'trace' => app()->environment('local') ? $exception->getTraceAsString() : null
            ], 419);
        }
        return parent::render($request, $exception);
    }
}
