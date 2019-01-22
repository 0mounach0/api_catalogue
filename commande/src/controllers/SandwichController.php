<?php
namespace lbs\controllers;

use lbs\models\Sandwich;

class SandwichController extends Controller {


//----------------sandwich---------------------------------------------

    //---------get sandwichs---------
    public function getSandwichs($req, $resp, $args){

        try{

            $sand = Sandwich::select()->get();
            
            $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'categorie' => $sand->toArray()
            ];

            $resp->withStatus(200) ;
            $resp->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode($data));
            return $resp;


        }catch(\Exception $e){



        }

    }


    //---------get sandwich by id-------------
    public function getSandwich($req, $resp, $args){

        try{

            $sand = Sandwich::where('id','=',$args['id'])->firstOrFail();
            
         
                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'categorie' => $sand->toArray()
            ];
            

            $resp->withStatus(200) ;
            $resp->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode($data));
            return $resp;


        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $data = [
                'type' => 'error',
                'error' => 404,
                'message' => 'ressource non disponible : /categorie/ '. $args['id']
            ];

            $resp->withStatus(404) ;
            $resp->withHeader('Content-Type', 'application/json;charset=utf-8');
            $resp->getBody()->write(json_encode($data));
            return $resp;


        }

    }


}



?>