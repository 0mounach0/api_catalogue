<?php
namespace lbs\errors;

/**
 * Class BadRequest
 * @package lbs\errors
 */
class BadRequest  {

    /**
     * @param $req
     * @param $resp
     * @return mixed
     */
    static public function error($req, $resp){

        $data = [
                    'type' => 'error',
                    'meta' => ['date' =>date('d-m-Y')],
                    "error" => 400,
                    "Message :" => "Bad request: l\'uri indiqué n'est pas connue de l'api"
                ];

            $resp->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->withStatus(400);
            $resp->getBody()->write(json_encode($data));
            return $resp;

    }

}