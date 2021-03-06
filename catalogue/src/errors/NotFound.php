<?php
namespace lbs\errors;

/**
 * Classe NotFound
 */
class NotFound  {

    /**
     * Function error
     *
     * @param $req
     * @param $resp
     * @return void
     */
    static public function error($req, $resp){

        $data = [
            'type' => 'error',
            'meta' => ['date' =>date('d-m-Y')],
            "error" => 404,
            "Message :" => "not found: Ressource non trouvée."
            ];

            $resp->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->withStatus(404);
            $resp->getBody()->write(json_encode($data));
            return $resp;

    }

}