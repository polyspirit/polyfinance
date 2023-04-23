<?php
namespace App\Traits;

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
    protected $errorMessage = '';
    protected $errorCode = 400;
    protected $errorData = null;

    protected $cookie = [];
    protected $cookieLifetime = 1440;

	/**
     * Return a success JSON response.
     */
	protected function success(mixed $data, ?string $message = null, int $code = 200): \Illuminate\Http\JsonResponse
	{
        $response = responseSuccess($data, $message, $code);

        foreach ($this->cookie as $key => $value) {
            $response->withCookie(cookie($key, $value, $this->cookieLifetime));
        }

		return $response;
	}

	/**
     * Return an error JSON response.
     */
	protected function error(?string $message = null, int $code = 400, mixed $data = null)
	{
        $message = $message ?: $this->errorMessage;
        $code = $code ?: $this->errorCode;
        $data = $data ?: $this->errorData;

        return responseError($message, $code, $data);
	}

    protected function forbiddenError(?string $message = null, mixed $data = null)
    {
        return $this->error($message, 403, $data);
    }


    protected function setCookie($key, $value) {
        $this->cookie[$key] = $value;
    }

}
