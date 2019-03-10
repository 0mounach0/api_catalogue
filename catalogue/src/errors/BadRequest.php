<?php
namespace lbs\errors;

    /**
     * Classe BadRequest
     * @param $req
     * @param $resp
     */

class BadRequest  {

    /**
     * Fonction error
     * @param $req
     * @param $resp
     * @return void
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