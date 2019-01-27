<?php
namespace lbs\controllers;

use lbs\models\Commande;

class PayementController extends Controller {

//----------------Payement------------------------------------

/* public function getCommandes($req, $resp, $args){

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

    //---------create categorie-----------
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
 */
    } 





}



?>