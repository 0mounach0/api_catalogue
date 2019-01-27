<?php
namespace lbs\errors;

class NotAllowed  {

    static public function error($req, $resp, $methods){

        $data = [
                    'type' => 'error',
                    'meta' => ['date' =>date('d-m-Y')],
                    "error" => 405,
                    "Message :" => "La Methode n'est pas autorisée" . implode(',', $methods)
                ];

            $resp->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->withStatus(405);
            $resp->getBody()->write(json_encode($data));
            return $resp;

    }

}