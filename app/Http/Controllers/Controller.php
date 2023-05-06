<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Traits\ApiResponser;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    use ApiResponser;


    // ** VALIDATION AND AUTHORIZATION **

    protected function validate(array $validationRules = []): bool
    {
        $request = request();

        if($request && $validationRules) {
            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                $this->errorMessage = 'Validation error';
                $this->errorCode = 406;
                $this->errorData = $validator->errors();
                return false;
            }
        }

        return true;
    }


    // ** OTHER **

    protected function addUserIdToRequest(Request &$request, $userId = null)
    {
        if (empty($request->user_id)) {

            if (is_null($userId) && !is_null(auth()->user())) {
                $userId = auth()->user()->id;
            }

            if (!is_null($userId)) {
                $request->merge(['user_id' => $userId]);
            } else {
                throw new \Exception('Unathorized', 401);
            }
        }
    }
}
