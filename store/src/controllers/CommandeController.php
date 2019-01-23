<?php
namespace lbs\controllers;

use lbs\models\Commande;

class CommandeController extends Controller {

//----------------Commande------------------------------------
    //---------get all Commandes-------
    public function getCommandes($req, $resp, $args){

        try{

            $cmd = Commande::select()->get();
            
            $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'Commande' => $cmd->toArray()
            ];

            return $this->jsonOutup($resp, 200, $data);
            
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
                'categorie' => $cmd->toArray(),
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
/* 
    //---------create categorie-----------
    public function createCategorie($req, $resp, $args){

        try{

            $jsonData = $req->getParsedBody();

            $categorie = new Categorie();

            $categorie->nom = filter_var($jsonData['nom'], FILTER_SANITIZE_STRING);
            $categorie->description = filter_var($jsonData['description'], FILTER_SANITIZE_STRING);

            // Create catetgorie
            if($categorie->save()) {

                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'categorie' => $categorie->toArray()
                ];

                return $this->jsonOutup($resp, 201, $data);

            }else {

                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'message' => 'categorie Not Created'
                ];

                return $this->jsonOutup($resp, 400, $data);
            }


        }catch(\Exception $e){



        }

    } */





}



?>