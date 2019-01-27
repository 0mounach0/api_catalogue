<?php
namespace lbs\controllers;

use lbs\models\Commande;
use lbs\models\Item;
use Ramsey\Uuid\Uuid;

class CommandeController extends Controller {

//----------------Commande------------------------------------
    //---------Create Commande-------
    public function createCommande($req, $resp, $args){

        try{

            $jsonData = $req->getParsedBody();

            if (!isset($jsonData['nom'])) return $response->withStatus(400);
            if (!isset($jsonData['mail'])) return $response->withStatus(400);
            if (!isset($jsonData['livraison'])) return $response->withStatus(400);
            //if (!isset($jsonData['item'])) return $response->withStatus(400);

            $cmd = new Commande();
            $cmd->id = Uuid::uuid4();
            $cmd->nom = filter_var($jsonData['nom'], FILTER_SANITIZE_SPECIAL_CHARS);
            $cmd->mail = filter_var($jsonData['mail'], FILTER_SANITIZE_SPECIAL_CHARS);
            $cmd->created_at = date("Y-m-d H:i:s");
            $cmd->updated_at = date("Y-m-d H:i:s");
            $cmd->livraison = filter_var($jsonData['livraison'], FILTER_SANITIZE_SPECIAL_CHARS);
            $cmd->token = bin2hex(openssl_random_pseudo_bytes(32));
            

            // Create commande
            if($cmd->save()) {

               /*  foreach ($jsonData['item'] as $key => $i) {
                    $url = 'http://api.catalogue.local:10080/sandwichs/'.$i['id'];
                    
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json'
                     ));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec($ch);
                    curl_close($ch);
               
                    $sand = json_decode($result, true);

                    $item = new Item();
                    $item->uri = '/sandwichs/'.$i['id'];
                    $item->libelle = $sand['sandwich']['nom'];
                    $item->quantite = $i['qte'];
                    $item->tarif = $sand['sandwich']['prix'];
                    $item->command_id = $cmd->id;

                    $item->save();
                } */

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
            
        }
    
        }catch(\Exception $e){


        }
    }

   

/*
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

*/



}



?>