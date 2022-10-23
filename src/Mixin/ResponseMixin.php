<?php

namespace Painlesscode\Spider\Mixin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

const ERRORMESSAGE = 'Something went wrong';

class ResponseMixin
{
    public const SUCCESS_MESSAGE_SESSION_KEY = 'success_message';
    public const ERROR_MESSAGE_SESSION_KEY = 'error_message';

    public static function report(): callable
    {
        return function ($condition, $successMessage = null, $statusCode = 200, $errorMessage = ERRORMESSAGE, $redirectAddress = null) {
            if ($successMessage === null) {
                $trace = Arr::first(debug_backtrace(), function ($trace) {
                    return Str::contains($trace['class'], 'Controller');
                });
                throw_unless(
                    $trace && in_array($trace['function'], ['store', 'update', 'destroy']),
                    new \Exception('Can not guess success message. please provide one in ->report(condition, {here})')
                );
                $successMessage = Str::afterLast(Str::beforeLast($trace['class'], 'Controller'), '\\')
                    . ' ' . ['store' => 'created', 'update' => 'updated', 'destroy' => 'deleted'][$trace['function']]
                    . ' successfully';
            }

            if (request()->wantsJson()) {
                if ($condition) {
                    return response()->json([
                        'type' => 'success',
                        'message' => $successMessage,
                    ], $statusCode);
                }

                return response()->json([
                    'type' => 'error',
                    'message' => $errorMessage,
                ], $statusCode);
            }

            if ($condition) {
                return ($redirectAddress ? redirect($redirectAddress) : back())->with(ResponseMixin::SUCCESS_MESSAGE_SESSION_KEY, $successMessage);
            }

            return back()->with(ResponseMixin::ERROR_MESSAGE_SESSION_KEY, $errorMessage);
        };
    }

    public static function reportTo(): callable
    {
        return function ($condition, $successMessage, $redirectAddress, $errorMessage = ERRORMESSAGE) {
            return $this->report($condition, $successMessage, 200, $errorMessage, $redirectAddress);
        };
    }

    public static function error(): callable
    {
        return function ($message = null, $statusCode = 200, $redirectAddress = null) {
            return $this->report(false, '', $statusCode, $message ?? ERRORMESSAGE, $redirectAddress);
        };
    }

    public static function success(): callable
    {
        return function ($message = null, $statusCode = 200, $redirectAddress = null) {
            return $this->report(true, $message, $statusCode, '', $redirectAddress);
        };
    }
}
