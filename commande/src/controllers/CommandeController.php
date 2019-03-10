<?php
namespace lbs\controllers;

use lbs\models\Commande;
use lbs\models\Item;
use lbs\models\User;
use Ramsey\Uuid\Uuid;
use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException ;
use Firebase\JWT\BeforeValidException;

/**
 * Class CommandeController
 * @package lbs\controllers
 */
class CommandeController extends Controller {


    /**
     * Creation commande
     * @param $req
     * @param $resp
     * @param $args
     * @return mixed|void
     */

    public function createCommande($req, $resp, $args){

        try{

            //------

            $jsonData = $req->getParsedBody();

            if (!isset($jsonData['mail'])) return $response->withStatus(400);
            if (!isset($jsonData['livraison'])) return $response->withStatus(400);
            if (!isset($jsonData['item'])) return $response->withStatus(400); 

            $cmd = new Commande();
            $cmd->id = Uuid::uuid4();
            $cmd->mail = filter_var($jsonData['mail'], FILTER_SANITIZE_SPECIAL_CHARS);
            $cmd->livraison = filter_var($jsonData['livraison'], FILTER_SANITIZE_SPECIAL_CHARS);
            $cmd->created_at = date("Y-m-d H:i:s");
            $cmd->updated_at = date("Y-m-d H:i:s");
            $cmd->token = bin2hex(openssl_random_pseudo_bytes(32));

            //--------

            $secret = "mounach";
            if($req->getHeader('Authorization') != null){
                
                $h = $req->getHeader('Authorization')[0] ;
                $tokenstring = sscanf($h, "Bearer %s")[0] ;
                $token = JWT::decode($tokenstring, $secret, ['HS512'] );
            
            
            if(empty($token)){
                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'message' => 'jwt token user absent'
                ];

                return $this->jsonOutup($rs, 403, $data);
            }else{

                if (!isset($jsonData['mail'])) return $response->withStatus(400);
                $mail = filter_var($jsonData['mail'], FILTER_SANITIZE_SPECIAL_CHARS);

                if( $token->umail == $mail ){

                    $user = User::select()->where('email','=',$mail)->firstOrFail();
                    $cmd->nom = $user->fullname;
                    $cmd->client_id = $user->id;

                }else {

                    $data = [
                        'type' => 'error',
                        'error' => 404,
                        'message' => 'user jwt token invalide pour la carte demandée'
                    ];

                    return $this->jsonOutup($rs, 404, $data);
                }

            }
                }else{
                    if (!isset($jsonData['nom'])) return $response->withStatus(400);
                    if (!isset($jsonData['mail'])) return $response->withStatus(400);
                    if (!isset($jsonData['livraison'])) return $response->withStatus(400);
                    if (!isset($jsonData['item'])) return $response->withStatus(400);

                    $cmd->nom = filter_var($jsonData['nom'], FILTER_SANITIZE_SPECIAL_CHARS);
                    
                }

            
            

            // Create commande
            if($cmd->save()) {

                $client = new Client([

                    // Base URL : pour ensuite transmettre des requêtes relatives
                    'base_uri' =>   $this->container->settings['catalogue'],
                    // options par défaut pour les requêtes
                    'timeout' => 2.0,
                   ]);
    
                   $montant = 0;

                   $items = [];

                 foreach ($jsonData['item'] as $key => $i) {
                    
                   $result = $client->get('/sandwichs/' . $i['id']);
    
                   $sand = json_decode($result->getBody());

                    $item = new Item();
                    $item->uri = '/sandwichs/'.$i['id'];
                    $item->libelle = $sand->sandwich->nom;
                    $item->quantite = $i['qte'];
                    $item->tarif = $sand->sandwich->prix;
                    $item->command_id = $cmd->id;

                    $item->save();

                    $montant += ($item->quantite * $item->tarif);

                    $items[] = [
                            'uri' => '/sandwichs/'.$i['id'],
                            'qte' => $i['qte']
                    ];
                } 

                $cmd->montant = $montant;
                $cmd->save();

                $data = [
                    'type' => 'resource',
                    'meta' => ['date' =>date('d-m-Y')],
                    'commande' => $cmd->toArray(),
                    'items' => $items
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

    /**
     * Recuperation des identifiants et detail des commandes
     * @param $req
     * @param $resp
     * @param $args
     * @return mixed|void
     */
     public function getCommande($req, $resp, $args){

        try{

            $cmd = Commande::select('id', 'livraison', 'nom', 'mail', 'status', 'montant')
                        ->where('id','=',$args['id'])    
                        ->firstOrFail();

            $items = $cmd->items()->select('uri','libelle','tarif','quantite')->get();
            
         
            $myCmd = [
                'id' => $cmd->toArray()['id'], 
                'livraison' => $cmd->toArray()['livraison'],
                'nom'=> $cmd->toArray()['nom'],
                'mail'=> $cmd->toArray()['mail'],
                'status'=> $cmd->toArray()['status'],
                'montant' => $cmd->toArray()['montant'],
                'items' => $items->toArray()
            ];

                $data = [
                    'type' => 'resource',
                    'date' =>date('d-m-Y'),
                    "links"=> [
                        "self"  => "/commandes/".$args['id']."/",
                        "items" => "/commandes/".$args['id']."/items/"
                    ],
                    'commande' => $myCmd
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

    /**
     * Mise à jour date de livraison
     * @param $req
     * @param $resp
     * @param $args
     * @return mixed|void
     */

     public function updateDateLivraison($req, $resp, $args){

        try{

            $jsonData = $req->getParsedBody();

            $cmd = Commande::where('id','=',$args['id'])->firstOrFail();

            if (!isset($jsonData['livraison'])) return $response->withStatus(400);

            $cmd->livraison = filter_var($jsonData['livraison'], FILTER_SANITIZE_SPECIAL_CHARS);

            // update date livraison
            if($cmd->save()) {

                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'commande' => $cmd->toArray()
                ];

                return $this->jsonOutup($resp, 201, $data);

            }else {

                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'message' => 'date livraison Not updated'
                ];

                return $this->jsonOutup($resp, 400, $data);
            }


        }catch(\Exception $e){



        }

    }

    /**
     * Recuperation facture
     * @param $req
     * @param $resp
     * @param $args
     * @return mixed|void
     */
    public function getFacture($req, $resp, $args){

        try{
            

            $cmd = Commande::select('id', 'livraison', 'nom', 'mail', 'status', 'montant')
                            ->where('id','=',$args['id'])->firstOrFail();

            if($cmd->status < 2){

                $data = ['type' => 'error',
                'meta' => ['date' =>date('d-m-Y')],
                'message' => "la commande n'est pas encore payé"
                ];

                return $this->jsonOutup($resp, 400, $data);

            }else{

                $items = $cmd->items()->select('uri','libelle','tarif','quantite')->get();
         
                $myCmd = [
                    'id' => $cmd->toArray()['id'], 
                    'livraison' => $cmd->toArray()['livraison'],
                    'nom'=> $cmd->toArray()['nom'],
                    'mail'=> $cmd->toArray()['mail'],
                    'status'=> $cmd->toArray()['status'],
                    'montant' => $cmd->toArray()['montant'],
                    'items' => $items->toArray()
                ];

                    $data = [
                        'type' => 'resource',
                        'date' =>date('d-m-Y'),
                        "links"=> [
                            "self"  => "/commandes/".$args['id']."/facture",
                            "items" => "/commandes/".$args['id']."/items/"
                        ],
                        'commande' => $myCmd
                ];
                
                return $this->jsonOutup($resp, 200, $data);

            }
                
            


        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $data = [
                'type' => 'error',
                'error' => 404,
                'message' => 'ressource non disponible : /Commande/ '. $args['id']
            ];

            return $this->jsonOutup($resp, 404, $data);


        }

    }
    





}



?>