<?php

namespace App\Models;

class AbstractModel
{
    protected $response = [];

    public function successResponse($action = '', $data = [], $message = '')
    {
        return [
            'data' => [
                'action' => $action,
                'item' => $data,
                'message' => $message
            ]
        ];
    }

    protected function errorResponse()
    {
        return [
            'error' => [
                'action' => 'error',
                'message' => 'There was an error Restart Game!'
            ]
        ];
    }
}