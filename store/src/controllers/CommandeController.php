<?php
namespace lbs\controllers;

use lbs\models\Commande;

class CommandeController extends Controller {

//----------------Commande------------------------------------
    //---------get all Commandes-------
    public function getCommandes($req, $resp, $args){

        try{

            $cmd = Commande::select('id', 'nom', 'created_at', 'livraison', 'status');

            $status = $req->getQueryParam('s', null);
            $page = $req->getQueryParam('page', 1);
            $size = $req->getQueryParam('size', 10);

            if(!is_null($status))
                $cmd = $cmd->where('status', '=', $status);

            $total = $cmd->count();

            $nbpageMax = ceil($total/$size);

            if($page > $nbpageMax){
                $page = $nbpageMax;
            }
               
            $cmd = $cmd->skip(($page - 1) * $size)->take($size);

            $countSize = $cmd->count();

            $cmd = $cmd->get();

            $myCmd = [];

            foreach ($cmd->toArray() as $key => $value) {
                $myCmd[] = [
                    "commande" =>  $value,
                    'links' => [
                        "self" => [ "href" => "/commandes/".$value['id']."/"] 
                    ]
                ];
            }

            $data = [
                "type"  => "collection",
                "count" => $total,
                "size"  => $size,
                "page"  => $page,
                "maxPages" => $nbpageMax,
                "links" => [
                    "next" => [
                    "href" => "/commandes/?page=".(($page >= $nbpageMax) ? $nbpageMax : ($page+1))."&size=".$size
                        ],
                    "prev"=> [
                    "href"=> "/commandes/?page=".(($page <= 1) ? 1 : ($page-1))."&size=".$size
                ],
                    "last"=> [
                    "href"=> "/commandes/?page=".$nbpageMax."&size=".$size
                ],
                    "first"=> [
                    "href"=> "/commandes/?page=1&size=".$size
                    ]
                    ],
                'commandes' => $myCmd
            ];

            return $this->jsonOutup($resp, 200, $data);
            
        }catch(\Exception $e){


        }
    }


    //---------get commande by id and commande items---------
    public function getCommande($req, $resp, $args){

        try{

            $cmd = Commande::select('id', 'created_at','livraison','nom','mail','montant')
                        ->where('id','=',$args['id'])->firstOrFail();

            $items = $cmd->items()->select('uri','libelle','tarif','quantite')->get();
            
            $myCmd = [
                'id' => $cmd->toArray()['id'], 
                'created_at' => $cmd->toArray()['created_at'],
                'livraison' => $cmd->toArray()['livraison'],
                'nom'=> $cmd->toArray()['nom'],
                'mail'=> $cmd->toArray()['mail'],
                'montant' => $cmd->toArray()['montant'],
                'items' => $items->toArray()
            ];
            
         
                $data = ['type' => 'resource',
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