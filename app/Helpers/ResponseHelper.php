<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function sendResponse($statusCode, $message = '', $data = null): JsonResponse
    {
        $response = ['status' => $statusCode < 300 ? 'success' : 'error', 'message' => $message];
        if ($data !== null) {
            $response['data'] = $data;
        }
        return response()->json($response, $statusCode);
    }

    public static function Success($message = 'Ok', $data = null): JsonResponse
    {
        return self::sendResponse(200, $message, $data);
    }

    public static function Created($message = 'Created', $data = null): JsonResponse
    {
        return self::sendResponse(201, $message, $data);
    }

    public static function Accepted($message = 'Accepted', $data = null): JsonResponse
    {
        return self::sendResponse(202, $message, $data);
    }

    public static function NoContent(): JsonResponse
    {
        return self::sendResponse(204, 'No Content');
    }

    public static function BadRequest($message = 'Bad Request'): JsonResponse
    {
        return self::sendResponse(400, $message);
    }

    public static function Unauthorized($message = 'Unauthorized'): JsonResponse
    {
        return self::sendResponse(401, $message);
    }

    public static function PaymentRequired($message = 'Payment Required'): JsonResponse
    {
        return self::sendResponse(402, $message);
    }

    public static function Forbidden($message = 'Forbidden'): JsonResponse
    {
        return self::sendResponse(403, $message);
    }

    public static function NotFound($message = 'Not Found'): JsonResponse
    {
        return self::sendResponse(404, $message);
    }

    public static function MethodNotAllowed($message = 'Method Not Allowed'): JsonResponse
    {
        return self::sendResponse(405, $message);
    }

    public static function NotAcceptable($message = 'Not Acceptable'): JsonResponse
    {
        return self::sendResponse(406, $message);
    }

    public static function ProxyAuthenticationRequired($message = 'Proxy Authentication Required'): JsonResponse
    {
        return self::sendResponse(407, $message);
    }

    public static function RequestTimeout($message = 'Request Timeout'): JsonResponse
    {
        return self::sendResponse(408, $message);
    }

    public static function Conflict($message = 'Conflict'): JsonResponse
    {
        return self::sendResponse(409, $message);
    }

    public static function Gone($message = 'Gone'): JsonResponse
    {
        return self::sendResponse(410, $message);
    }

    public static function LengthRequired($message = 'Length Required'): JsonResponse
    {
        return self::sendResponse(411, $message);
    }

    public static function PreconditionFailed($message = 'Precondition Failed'): JsonResponse
    {
        return self::sendResponse(412, $message);
    }

    public static function PayloadTooLarge($message = 'Payload Too Large'): JsonResponse
    {
        return self::sendResponse(413, $message);
    }

    public static function URITooLong($message = 'URI Too Long'): JsonResponse
    {
        return self::sendResponse(414, $message);
    }

    public static function UnsupportedMediaType($message = 'Unsupported Media Type'): JsonResponse
    {
        return self::sendResponse(415, $message);
    }

    public static function RangeNotSatisfiable($message = 'Range Not Satisfiable'): JsonResponse
    {
        return self::sendResponse(416, $message);
    }

    public static function ExpectationFailed($message = 'Expectation Failed'): JsonResponse
    {
        return self::sendResponse(417, $message);
    }

    public static function ImATeapot($message = 'I\'m a teapot'): JsonResponse
    {
        return self::sendResponse(418, $message);
    }

    public static function MisdirectedRequest($message = 'Misdirected Request'): JsonResponse
    {
        return self::sendResponse(421, $message);
    }

    public static function UnprocessableEntity($message = 'Unprocessable Entity'): JsonResponse
    {
        return self::sendResponse(422, $message);
    }

    public static function Locked($message = 'Locked'): JsonResponse
    {
        return self::sendResponse(423, $message);
    }

    public static function FailedDependency($message = 'Failed Dependency'): JsonResponse
    {
        return self::sendResponse(424, $message);
    }

    public static function TooEarly($message = 'Too Early'): JsonResponse
    {
        return self::sendResponse(425, $message);
    }

    public static function UpgradeRequired($message = 'Upgrade Required'): JsonResponse
    {
        return self::sendResponse(426, $message);
    }

    public static function PreconditionRequired($message = 'Precondition Required'): JsonResponse
    {
        return self::sendResponse(428, $message);
    }

    public static function TooManyRequests($message = 'Too Many Requests'): JsonResponse
    {
        return self::sendResponse(429, $message);
    }

    public static function RequestHeaderFieldsTooLarge($message = 'Request Header Fields Too Large'): JsonResponse
    {
        return self::sendResponse(431, $message);
    }

    public static function UnavailableForLegalReasons($message = 'Unavailable For Legal Reasons'): JsonResponse
    {
        return self::sendResponse(451, $message);
    }

    public static function InternalServerError($message = 'Internal Server Error'): JsonResponse
    {
        return self::sendResponse(500, $message);
    }

    public static function NotImplemented($message = 'Not Implemented'): JsonResponse
    {
        return self::sendResponse(501, $message);
    }

    public static function BadGateway($message = 'Bad Gateway'): JsonResponse
    {
        return self::sendResponse(502, $message);
    }

    public static function ServiceUnavailable($message = 'Service Unavailable'): JsonResponse
    {
        return self::sendResponse(503, $message);
    }

    public static function GatewayTimeout($message = 'Gateway Timeout'): JsonResponse
    {
        return self::sendResponse(504, $message);
    }

    public static function HTTPVersionNotSupported($message = 'HTTP Version Not Supported'): JsonResponse
    {
        return self::sendResponse(505, $message);
    }

    public static function VariantAlsoNegotiates($message = 'Variant Also Negotiates'): JsonResponse
    {
        return self::sendResponse(506, $message);
    }

    public static function InsufficientStorage($message = 'Insufficient Storage'): JsonResponse
    {
        return self::sendResponse(507, $message);
    }

    public static function LoopDetected($message = 'Loop Detected'): JsonResponse
    {
        return self::sendResponse(508, $message);
    }

    public static function NotExtended($message = 'Not Extended'): JsonResponse
    {
        return self::sendResponse(510, $message);
    }

    public static function NetworkAuthenticationRequired($message = 'Network Authentication Required'): JsonResponse
    {
        return self::sendResponse(511, $message);
    }
}
