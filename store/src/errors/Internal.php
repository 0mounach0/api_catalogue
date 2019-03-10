<?php
namespace lbs\errors;

/**
 * Class Internal
 * @package lbs\errors
 */
class Internal  {

    /**
     * @param $req
     * @param $resp
     * @param $exception
     * @return mixed
     */
    static public function error($req, $resp, $exception){

        $data = [
                    'type' => 'error',
                    'meta' => ['date' =>date('d-m-Y')],
                    "error" => 500,
                    "Message :" => "Something went wrong!: Internal Server Error",
                    "exception :" => $exception->getMessage(),
                    'file : ' => $exception->getFile(),
                    'line : ' => $exception->getLine()  
                  ];

            $resp->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->withStatus(500);
            $resp->getBody()->write(json_encode($data));
            return $resp;

    }

}