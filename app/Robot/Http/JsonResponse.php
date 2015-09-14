<?php namespace Robot\Http;

use Illuminate\Support\Facades\Response;

class JsonResponse {

    public function make($code, $message = array(), $status = 200, $header = array())
    {
        $jsonArray = array(
            'code' => $code,
            'message' => $message,
            '_token' => csrf_token(),
        );

        return Response::json($jsonArray, $status, $header);
    }

}
