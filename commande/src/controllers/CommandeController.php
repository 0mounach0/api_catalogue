<?php
namespace lbs\controllers;

use lbs\models\Commande;

class CommandeController extends Controller {

//----------------Commande------------------------------------
    //---------Create Commande-------
    public function createCommande($req, $resp, $args){

        try{

            $jsonData = $req->getParsedBody();

            if (!isset($jsonData['nom_client'])) return $response->withStatus(400);
            if (!isset($jsonData['mail_client'])) return $response->withStatus(400);
            if (!isset($jsonData['date'])) return $response->withStatus(400);
            if (!isset($jsonData['heure'])) return $response->withStatus(400);

            $cmd = new Commande();
            $cmd->id = Uuid::uuid4();
            $cmd->nom_client = filter_var($jsonData['nom_client'], FILTER_SANITIZE_SPECIAL_CHARS);
            $cmd->mail_client = filter_var($jsonData['mail_client'], FILTER_SANITIZE_SPECIAL_CHARS);
            $cmd->date = filter_var($jsonData['date'], FILTER_SANITIZE_SPECIAL_CHARS);
            $cmd->token = bin2hex(openssl_random_pseudo_bytes(32));
            

            // Create commande
            if($cmd->save()) {

                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'commande' => $cmd->toArray()
                ];

                return $this->jsonOutup($resp, 201, $data);

            }else {

                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'message' => 'commande Not Created'
                ];

                return $this->jsonOutup($resp, 400, $data);
            
        }catch(\Exception $e){


        }
    }


    //---------get commande by id and commande items---------
    public function getCommande($req, $resp, $args){

        try{

            $cmd = Commande::where('id','=',$args['id'])->firstOrFail();
            $items = $cmd->items()->get();
            
         
                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'commande' => $cmd->toArray(),
                'item' => $items->toArray()
            ];
            

            return $this->jsonOutup($resp, 200, $data);


        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $data = [
                'type' => 'error',
                'error' => 404,
                'message' => 'ressource non disponible : /Commande/ '. $args['id']
            ];

            return $this->jsonOutup($resp, 404, $data);


        }

    }

    //---------updateStatus-----------
    public function updateStatus($req, $resp, $args){

        try{

            $jsonData = $req->getParsedBody();

            $cmd = Commande::where('id','=',$args['id'])->firstOrFail();

            $cmd->status = filter_var($jsonData['status'], FILTER_VALIDATE_INT);

            // update status
            if($cmd->save()) {

                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'commande' => $cmd->toArray()
                ];

                return $this->jsonOutup($resp, 201, $data);

            }else {

                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'message' => 'status Not updated'
                ];

                return $this->jsonOutup($resp, 400, $data);
            }


        }catch(\Exception $e){



        }

    } 





}



?>