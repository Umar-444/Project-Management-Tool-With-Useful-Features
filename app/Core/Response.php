<?php

/**
 * Response utility class for standardized JSON responses
 */
class Response
{
    /**
     * Send success response
     */
    public static function success($data = null, $message = 'Success', $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json');

        $response = [
            'success' => true,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        echo json_encode($response);
        exit();
    }

    /**
     * Send error response
     */
    public static function error($message, $code = 400, $errors = null)
    {
        http_response_code($code);
        header('Content-Type: application/json');

        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        echo json_encode($response);
        exit();
    }

    /**
     * Send validation error response
     */
    public static function validationError($errors)
    {
        self::error('Validation failed', 422, $errors);
    }

    /**
     * Send not found response
     */
    public static function notFound($message = 'Resource not found')
    {
        self::error($message, 404);
    }

    /**
     * Send method not allowed response
     */
    public static function methodNotAllowed()
    {
        self::error('Method not allowed', 405);
    }
}
