<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
//use Dotenv\Exception\ValidationException;
use Illuminate\Validation\ValidationException;  
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function Psy\debug;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }
        elseif($e instanceof ModelNotFoundException) {
            $modelName = $e->getModel();
            return errorResponse('the specified indicator does not exsist without any existence of the {$modelName}', 404);
        }
        elseif ($e instanceOf AuthenticationException) {
            return $this->unauthenticated($request, $e);
        }
        elseif($e instanceof AthorizationException) {
            return $this->errorResponse($e->getMessge(), 403);
        }
        elseif($e instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('sory the specified meethod for ` the request is invalid', 405);
        }
        elseif($e instanceof NotFoundHttpException) {
            return $this->errorResponse('sorry this page could not be found', 404);
        }
        elseif($e instanceof HttpException) {
            return $this->errorResponse($e->getMessage(), $e->getStatusCode());
        }
        // elseif($e instanceof QueryException) {
        //     $errorCode = $e->errorInfo[1];
        // }
        // elseif($errorCode == 1451) {
        //     return $this->errorResponse('cannot delete this resource parmanently. it is related with another resource', 409);
        // }

        elseif(config('app.debug')) {
        return parent::render($request, $e);            
        }

        return $this->errorResponse('Unexpected Exception. Try later', 500);

    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
     $errors = $e->validator->errors()->getMessages();

     return response()->json($errors, 422);

    }
}

