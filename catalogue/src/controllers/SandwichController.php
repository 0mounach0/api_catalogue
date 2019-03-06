<?php
namespace lbs\controllers;

use lbs\models\Sandwich;
use lbs\models\Categorie;

class SandwichController extends Controller {


//----------------sandwich---------------------------------------------

    //---------get sandwichs---------
    public function getSandwichs($req, $resp, $args){

        try{

            $type_pain = $req->getQueryParam('type_pain', null);
            $prix_max = $req->getQueryParam('prix_max', null);
            $page = $req->getQueryParam('page', 1);
            $size = $req->getQueryParam('size', 5);

            $sand = Sandwich::select();

            if(!is_null($type_pain))
                $sand = $sand->where('type_pain','like','%'.$type_pain.'%');

            if(!is_null($prix_max))
                $sand = $sand->where('prix','<=',$prix_max);

            $total = $sand->count();

            $nbpageMax = ceil($total/$size);

            if($page > $nbpageMax){
                $page = $nbpageMax;
            }
               
            $sand = $sand->skip(($page - 1) * $size)->take($size);

            $countSize = $sand->count();

            $sand = $sand->get();

            $mySand = [];

            foreach ($sand->toArray() as $key => $value) {
                $mySand[] = [
                    "sandwich" =>  $value,
                    'links' => [
                        "self" => [ "href" => "/sandwichs/".$value['id']."/"] 
                    ]
                ];
            }

            $data = [
                "type"  => "collection",
                "count" => $total,
                "size"  => $size,
                "page"  => $page,
                "maxPages" => $nbpageMax,
                'sandwichs' => $mySand
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
                'date' => date('d-m-Y'),
                'sandwich' => $sand->toArray(),
                "links"=> [
                    "categories"=> [ "href" => "/sandwichs/".$args['id']."/categories/" ],
                    "self" => [ "href" => "/sandwichs/".$args['id']."/" ]
                ]
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



    //------twig----------

    //---------get sandwichs---------
    public function showAllSandwichs($req, $resp, $args){
         try{
            $sandwichs = null;

            $cat_id = $req->getQueryParam('cat', null);
            
            if(is_null($cat_id))
                $sandwichs = Sandwich::Select()->get();
            else{
                $cat = Categorie::select('id','nom')->where('id','=',$cat_id)->firstOrFail();
                $sandwichs = $cat->sandwichs()->select()->get();
            }
            
                $categories = Categorie::Select()->get();

            return $this->container->view->render($resp, 'home.twig',[
                'sandwichs' => $sandwichs->toArray(), 
                'categories' => $categories->toArray() 
                ]);

        }catch(\Exception $e){



        } 

    }


}



?>