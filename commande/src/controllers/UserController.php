<?php
namespace lbs\controllers;

use lbs\models\Commande;
use lbs\models\Item;
use lbs\models\User;
use Ramsey\Uuid\Uuid;
use GuzzleHttp\Client;
use Firebase\JWT\JWT;

class UserController extends Controller {

    //---------create user-----------
    public function createUser($req, $resp, $args){

        try{

            $jsonData = $req->getParsedBody();

            $user = new User();

            $user->fullname = filter_var($jsonData['fullname'], FILTER_SANITIZE_STRING);
            $user->email = filter_var($jsonData['email'], FILTER_SANITIZE_STRING);
            $user->carte = filter_var($jsonData['carte'], FILTER_SANITIZE_STRING);
            $user->date_expiration = filter_var($jsonData['date_expiration'], FILTER_SANITIZE_STRING);
            $user->password = password_hash( filter_var($jsonData['password'], FILTER_SANITIZE_STRING) ,PASSWORD_DEFAULT);
            $user->birth_day = date('Y-m-d',strtotime(filter_var($jsonData['birth_day'], FILTER_SANITIZE_STRING)));
            $user->cumul = 0;

            // Create catetgorie
            if($user->save()) {

                $data = ['type' => 'resource',
                'date' =>date('d-m-Y'),
                'user' => $user->toArray()
                ];

                return $this->jsonOutup($resp, 201, $data);

            }else {

                $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'message' => 'user Not Created'
                ];

                return $this->jsonOutup($resp, 400, $data);
            }


        }catch(\Exception $e){



        }

    }


    //--------- login user -----
    public function loginUser($req, $resp, $args){

        try{

                $username = null;
                $password = null;

                // mod_php
                if (isset($_SERVER['PHP_AUTH_USER'])) {

                    $username = $_SERVER['PHP_AUTH_USER'];
                    $password = $_SERVER['PHP_AUTH_PW'];

                // most other servers
                } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {

                        if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']),'basic')===0)
                        list($username,$password) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));

                }

               
                    
                    $user = User::select()->where('email','=', $username)
                                          ->firstOrFail();

                    if(password_verify($password, $user->password)){

                        $secret = "mounach";

                        $token = JWT::encode( [ 'iss'=>'http://api.commande.local',
                                                'aud'=>'http://api.commande.local',
                                                'iat'=>time(), 'exp'=>time()+3600,
                                                'uid' => $user->id,
                                                'umail' => $user->email,
                                                'lvl' => 1 ],
                                                $secret, 'HS512' );

                        $data = [
                            "token" => $token
                        ];

                        return $this->jsonOutup($resp, 201, $data);

                    }else{
                                
                    $data = ['type' => 'resource',
                    'meta' => ['date' =>date('d-m-Y')],
                    'message' => 'no authorization header present'
                    ];

                    return $this->jsonOutup($resp, 401, $data);

                    }
                    


        }catch(\Exception $e){



        }

    }

    //------------ get user infos ---------

    public function getUser($req, $resp, $args){

        try{

            $user = User::select()->where('id','=',$args['id'])->firstOrFail();
         
            $myUser = [
                'id' => $user->toArray()['id'], 
                'fullname' => $user->toArray()['fullname'],
                'email'=> $user->toArray()['email'],
                'birth_day'=> $user->toArray()['birth_day'],
                'cumul'=> $user->toArray()['cumul']
            ];

                $data = [
                    'type' => 'resource',
                    'date' =>date('d-m-Y'),
                    "links"=> [
                        "self"  => "/users/".$args['id']."/"
                    ],
                    'user' => $myUser
            ];
            

            return $this->jsonOutup($resp, 200, $data);


        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $data = [
                'type' => 'error',
                'error' => 404,
                'message' => 'ressource non disponible : /users/ '. $args['id']
            ];

            return $this->jsonOutup($resp, 404, $data);


        }

    }


    //---------payerCommande-----------
    public function payerCommande($req, $resp, $args){

        try{

            $jsonData = $req->getParsedBody();

            $cmd = Commande::where('id','=',$args['id'])->firstOrFail();

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

                return $this->jsonOutup($resp, 403, $data);
            }else{

                if (!isset($jsonData['payement_choice'])) return $resp->withStatus(400);
                $payement_choice = filter_var($jsonData['payement_choice'], FILTER_SANITIZE_SPECIAL_CHARS);

                $user = User::Select()->where('id','=', $token->uid)->firstOrFail();

                //utiliser le cumul
                if($payement_choice == 1){
                    $remise = (5 * $user->cumul) / 100; 
                    $cmd->remise = $remise;
                    $user->cumul = 0;
                }else if($payement_choice == 2){
                    //payer par carte
                    $user->cumul = $user->cumul + $cmd->montant;
                }

                if($user->save()) {

                   //okkkk
    
                }else {
    
                    $data = ['type' => 'resource',
                    'meta' => ['date' =>date('d-m-Y')],
                    'message' => 'user payement error'
                    ];
    
                    return $this->jsonOutup($resp, 400, $data);
                }

            }
                }else{
                
                    //---------------------
                    if (!isset($jsonData['carte'])) return $resp->withStatus(400);
                    if (!isset($jsonData['date_expiration'])) return $resp->withStatus(400);
                    
                }

                //------

            

            $cmd->ref_paiement = bin2hex(openssl_random_pseudo_bytes(48));
            $cmd->date_paiement = date("Y-m-d H:i:s");
            $cmd->mode_paiement = 1;
            $cmd->status = 2;

            // update 
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

    //------------ get user commands history -----------

    public function getUserCommandes($req, $resp, $args){

        try{

            $user = User::select()->where('id','=',$args['id'])->firstOrFail();
         
            $cmds = Commande::select()->where('client_id','=', $user->id)->get();

                $data = [
                    'type' => 'resource',
                    'date' =>date('d-m-Y'),
                    "links"=> [
                        "self"  => "/users/".$args['id']."/commandes"
                    ],
                    'commandes' => $cmds
            ];
            

            return $this->jsonOutup($resp, 200, $data);


        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $data = [
                'type' => 'error',
                'error' => 404,
                'message' => 'ressource non disponible : /users/ '. $args['id']
            ];

            return $this->jsonOutup($resp, 404, $data);


        }

    }



}



?>