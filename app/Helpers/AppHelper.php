<?php

if (!function_exists('vdd')) {
    function vdd(mixed $var): void
    {
        var_dump($var);
        die();
    }
}

if (!function_exists('jvdd')) {
    function jvdd(mixed $var): void
    {
        echo json_encode($var, JSON_UNESCAPED_UNICODE);
        die();
    }
}

if (!function_exists('responseSuccess')) {
    function responseSuccess(mixed $data, ?string $message = null, int $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
			'status' => 'success',
			'message' => $message,
			'data' => $data
		], $code);
    }
}

if (!function_exists('responseError')) {
    function responseError(?string $message = null, int $code = 400, mixed $data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
			'status' => 'error',
			'message' => $message,
			'data' => $data
		], $code);
    }
}

if (!function_exists('camelCaseToUnderscore')) {
    function camelCaseToUnderscore(string $input)
    {
        return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $input)), '_');
    }
}

if (!function_exists('getUnderscoreClassName')) {
    function getUnderscoreClassName(string $class)
    {
        $classNameParts = explode('\\', $class);
        return camelCaseToUnderscore($classNameParts[count($classNameParts) - 1]);
    }
}
