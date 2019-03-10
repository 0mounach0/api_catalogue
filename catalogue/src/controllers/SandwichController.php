<?php
namespace lbs\controllers;

use lbs\models\Sandwich;
use lbs\models\Categorie;

/**
 * Classe SandwichControler extends la classe Controller
 */
class SandwichController extends Controller {

    /**
     * Sandwich
     * Les sandwichs
     * @param $req
     * @param $resp
     * @param $args
     */
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

    /**
     * Sandwich par ID
     * @param $req
     * @param $resp
     * @param $args
     */
  
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


    /**
     * Avoir les catégories sandwich
     * @param $req
     * @param $resp
     * @param $args
     */
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


    /**
     * Twig
     * Avoir les sandwichs
     * @param $req
     * @param $resp
     * @param $args
     * home.twig
     */
    public function showAllSandwichs($req, $resp, $args){
         try{
             if($this->container->auth->check()){
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

            }else{
                return $resp->withRedirect($this->container->router->pathFor('login'));
            }

        }catch(\Exception $e){



        } 

    }



     /**
     * Form des sandwichs
     * @param $req
     * @param $resp
     * @param $args
     * createSandwichForm.twig
     */

    public function createSandwichForm($req, $resp, $args){
        try{
            if($this->container->auth->check()){
               $categories = Categorie::Select()->get();

           return $this->container->view->render($resp, 'createSandwichForm.twig',[
               'categories' => $categories->toArray() 
               ]);

            }else{
                return $resp->withRedirect($this->container->router->pathFor('login'));
            }

       }catch(\Exception $e){


       } 

   }


    /**
     * Editer le sandwich form
     * @param $req
     * @param $resp
     * @param $args
     * editSandwichForm.twig
     */

   public function editSandwichForm($req, $resp, $args){
    try{
        if($this->container->auth->check()){
        $sandwich = Sandwich::find($args['id']);

       return $this->container->view->render($resp, 'editSandwichForm.twig',[
           'sandwich' => $sandwich->toArray() 
           ]);
        }else{
            return $resp->withRedirect($this->container->router->pathFor('login'));
        }
   }catch(\Exception $e){


   } 

}



     /**
     * Créer un sandwich
     * @param $req
     * @param $resp
     * @param $args
     */

    public function createSandwich($req, $resp, $args){
        try{
            if($req->getParam('categorie')){
                $sandwich = new Sandwich();
                $sandwich->nom = filter_var($req->getParam('nom'), FILTER_SANITIZE_STRING);
                $sandwich->description = filter_var($req->getParam('description'), FILTER_SANITIZE_STRING);
                $sandwich->prix = (float) filter_var($req->getParam('prix'), FILTER_SANITIZE_STRING);
                $sandwich->type_pain = filter_var($req->getParam('type'), FILTER_SANITIZE_STRING);

             /**
             * Créer un sandwich
             */
            if($sandwich->save()) {

                $sandwich->categories()->sync($req->getParam('categorie'));

                $this->showAllSandwichs($req, $resp, $args);
            }
            
            }else{
                echo "<script>alert('error');</script>";
                $this->createSandwichForm($req, $resp, $args);
            } 
                            
       }catch(\Exception $e){

    } 

}

    /**
     * Créer un sandwich
     * @param $req
     * @param $resp
     * @param $args
     */
    
   public function editSandwich($req, $resp, $args){
    try{
        
            $sandwich = Sandwich::find($args['id']);
            $sandwich->nom = filter_var($req->getParam('nom'), FILTER_SANITIZE_STRING);
            $sandwich->description = filter_var($req->getParam('description'), FILTER_SANITIZE_STRING);
            $sandwich->prix = (float) filter_var($req->getParam('prix'), FILTER_SANITIZE_STRING);
            $sandwich->type_pain = filter_var($req->getParam('type'), FILTER_SANITIZE_STRING);

         /**
         * Créer un sandwich
         */
        if($sandwich->save()) {

            $this->showAllSandwichs($req, $resp, $args);

        }
        
                        
   }catch(\Exception $e){

   } 

}

   
     /** 
      * Supprimer un sandwich
     * @param $req
     * @param $resp
     * @param $args
     */
   public function deleteSandwich($req, $resp, $args){
    try{
        if($this->container->auth->check()){
        $sandwich = Sandwich::find($args['id']);

        $sandwich->categories()->detach();

        $sandwich->delete();

        $this->showAllSandwichs($req, $resp, $args);
    }else{
        return $resp->withRedirect($this->container->router->pathFor('login'));
    }
   }catch(\Exception $e){


   } 

}



}



?>