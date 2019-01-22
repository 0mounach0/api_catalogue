<?php
namespace lbs\controllers;

use lbs\models\Sandwich;
use lbs\models\Categorie;

class SandwichController extends Controller {


//----------------sandwich---------------------------------------------

    //---------get sandwichs---------
    public function getSandwichs($req, $resp, $args){

        try{

            $sand = Sandwich::select()->get();
            
            $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'sandwichs' => $sand->toArray()
            ];

            return $this->jsonOutup($resp, 200, $data);


        }catch(\Exception $e){



        }

    }


    //---------get sandwich by id-------------
    public function getSandwich($req, $resp, $args){

        try{

            $sand = Sandwich::where('id','=',$args['id'])->firstOrFail();
            
         
                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'sandwich' => $sand->toArray()
            ];
            

            return $this->jsonOutup($resp, 200, $data);


        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $data = [
                'type' => 'error',
                'error' => 404,
                'message' => 'ressource non disponible : /sandwichs/ '. $args['id']
            ];

            return $this->jsonOutup($resp, 404, $data);


        }

    }

    //---------get Categorie Sandwichs-------------
    public function getCategorieSandwichs($req, $resp, $args){

        try{

            $cat = Categorie::select('id','nom')->where('id','=',$args['id'])->firstOrFail();

            $sandwichs = $cat->sandwichs()->select('id','nom','description')->get();
            
         
                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'categorie' => $cat->toArray(),
                'sandwichs' => $sandwichs->toArray()
            ];
            

            return $this->jsonOutup($resp, 200, $data);


        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $data = [
                'type' => 'error',
                'error' => 404,
                'message' => 'ressource non disponible : /categories/ '. $args['id'] . '/sandwichs'
            ];

            return $this->jsonOutup($resp, 404, $data);


        }

    }


}



?>