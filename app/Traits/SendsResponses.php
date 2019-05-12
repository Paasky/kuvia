<?php

namespace App\Traits;

use App\System;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait SendsResponses
{
    public static function getResponse($data, string $message, int $code) : JsonResponse
    {
        $responseData = [
            'data' => $data,
            'message' => $message ?: System::getMessageFromHtmlCode($code),
        ];

        if (System::isHtmlCodeAnError($code)) {
            $responseData['error'] = $code;
        } else {
            $responseData['success'] = $code;
        }

        return response()->json(
            $responseData,
            $code,
            [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * @param array|\object $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getOkResponse($data = [], string $message = '') : JsonResponse
    {
        return $this::getResponse($data, $message, Response::HTTP_OK);
    }

    /**
     * @param array|\object $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getCreatedResponse($data, string $message = '') : JsonResponse
    {
        return $this::getResponse($data, $message, Response::HTTP_CREATED);
    }

    /**
     * @param array|\object $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getUpdatedResponse($data, string $message = '') : JsonResponse
    {
        return $this::getResponse($data, $message, Response::HTTP_ACCEPTED);
    }

    /**
     * @param array|\object $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getNotModifiedResponse($data = [], $message = '') : JsonResponse
    {
        return $this::getResponse($data, $message, Response::HTTP_NOT_EXTENDED);
    }

    /**
     * @param array|\object $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getBadRequestResponse($data = [], $message = '') : JsonResponse
    {
        return $this::getResponse($data, $message, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param array|\object $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getUnauthorizedResponse($data = [], $message = '') : JsonResponse
    {
        return $this::getResponse($data, $message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param array|\object $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getForbiddenResponse($data = [], $message = '') : JsonResponse
    {
        return $this::getResponse($data, $message, Response::HTTP_FORBIDDEN);
    }

    /**
     * @param array|\object $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getNotFoundResponse($data = [], $message = '') : JsonResponse
    {
        return $this::getResponse($data, $message, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param array|\object $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getUnprocessableResponse($data = [], $message = '') : JsonResponse
    {
        return $this::getResponse($data, $message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getErrorResponse($e) : JsonResponse
    {
        // todo send stack trace depending on user capabilities
        $data = $e->getTrace();
        $message = $e->getMessage();

        return $this::getResponse($data, $message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
