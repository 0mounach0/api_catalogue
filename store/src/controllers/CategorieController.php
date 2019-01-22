<?php
namespace lbs\controllers;

use lbs\models\Categorie;

class CategorieController extends Controller {

//----------------categorie------------------------------------
    //---------get all categories-------
    public function getCategories($req, $resp, $args){

        try{

            $cat = Categorie::select()->get();
            
            $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'categorie' => $cat->toArray()
            ];

            return $this->jsonOutup($resp, 200, $data);
            
        }catch(\Exception $e){


        }
    }


    //---------get categorie by id---------
    public function getCategorie($req, $resp, $args){

        try{

            $cat = Categorie::where('id','=',$args['id'])->firstOrFail();
            
         
                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'categorie' => $cat->toArray()
            ];
            

            return $this->jsonOutup($resp, 200, $data);


        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $data = [
                'type' => 'error',
                'error' => 404,
                'message' => 'ressource non disponible : /categorie/ '. $args['id']
            ];

            return $this->jsonOutup($resp, 404, $data);


        }

    }

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

    }





}



?>