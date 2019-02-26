<?php
namespace lbs\middlewares;

use lbs\models\Commande;
use lbs\models\User;
use lbs\controllers\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException ;
use Firebase\JWT\BeforeValidException;


class Token extends Controller {

    //------------------

    public function check ($rq, $rs, $next){

        $token = $rq->getQueryParam('token', null);
        if(is_null($token))
            $token = $rq->getHeader('X-lbs-token');

        if(empty($token)){
            $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'message' => 'token commande absent'
                ];

                return $this->jsonOutup($rs, 403, $data);
        }

        try{
            $cmd = Commande::where('token','=', $token)->firstOrFail();

        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $data = [
                'type' => 'error',
                'error' => 404,
                'message' => 'ressource non disponible : /Commande/ '. $args['id']
            ];

            return $this->jsonOutup($rs, 404, $data);


        }

        return $next($rq, $rs);


    }

    //------------------

    public function checkJwt ($rq, $rs, $next){
        try {

        $secret = "mounach";
        $h = $rq->getHeader('Authorization')[0] ;
        $tokenstring = sscanf($h, "Bearer %s")[0] ;
        $token = JWT::decode($tokenstring, $secret, ['HS512'] );

        if(empty($token)){
            $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'message' => 'jwt token user absent'
                ];

                return $this->jsonOutup($rs, 403, $data);
        }

        $route = $rq->getAttribute('route');
        $userId = $route->getArgument('id');

        if( $token->uid == $userId ){

            return $next($rq, $rs);            

        }else {
            $data = [
                'type' => 'error',
                'error' => 404,
                'message' => 'user jwt token invalide pour la carte demandée'
            ];

            return $this->jsonOutup($rs, 404, $data);
        }

            } catch (ExpiredException $e) {
                $data = [
                    'type' => 'error',
                    'error' => 404,
                    'message' => 'token Expired'
                ];
    
                return $this->jsonOutup($rs, 404, $data);
            } catch (SignatureInvalidException $e) {
                $data = [
                    'type' => 'error',
                    'error' => 404,
                    'message' => 'SignatureInvalidException'
                ];
    
                return $this->jsonOutup($rs, 404, $data);
            } catch (BeforeValidException $e) {
                $data = [
                    'type' => 'error',
                    'error' => 404,
                    'message' => 'BeforeValidException'
                ];
    
                return $this->jsonOutup($rs, 404, $data);
            } catch (\UnexpectedValueException $e) {
                $data = [
                    'type' => 'error',
                    'error' => 404,
                    'message' => 'UnexpectedValueException'
                ];
    
                return $this->jsonOutup($rs, 404, $data);
             }
    

       


    }


}