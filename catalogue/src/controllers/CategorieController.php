<?php
namespace lbs\controllers;

use lbs\models\Categorie;
use lbs\models\Sandwich;

class CategorieController extends Controller {

//----------------categorie------------------------------------
    //---------get all categories-------
    public function getCategories($req, $resp, $args){

        try{

            $cat = Categorie::select()->get();
            $total = $cat->count();
            $data = [
                'type' => 'collection',
                'date' =>date('d-m-Y'),
                'count' => $total,
                'categories' => $cat->toArray()
            ];

            return $this->jsonOutup($resp, 200, $data);
            
        }catch(\Exception $e){


        }
    }


    //---------get categorie by id---------
    public function getCategorie($req, $resp, $args){

        try{

            $cat = Categorie::where('id','=',$args['id'])->firstOrFail();
            
         
                $data = [
                    'type' => 'resource',
                    'date' => date('d-m-Y'),
                    'categorie' => $cat->toArray(),
                    "links"=> [
                        "sandwichs"=> [ "href" => "/categories/".$args['id']."/sandwichs/" ],
                        "self" => [ "href" => "/categories/".$args['id']."/" ]
                    ]
            ];
            

            return $this->jsonOutup($resp, 200, $data);


        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $data = [
                'type' => 'error',
                'error' => 404,
                'message' => 'ressource non disponible : /categories/ '. $args['id']
            ];

            return $this->jsonOutup($resp, 404, $data);


        }

    }


    //---------get sandwich categories-------------
    public function getSandwichCategories($req, $resp, $args){

        try{

            $sand = Sandwich::select()->where('id','=',$args['id'])->firstOrFail();

            $categories = $sand->categories()->get();
            
         
                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'sandwich' => $sand->toArray(),
                'categories' => $categories->toArray()
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