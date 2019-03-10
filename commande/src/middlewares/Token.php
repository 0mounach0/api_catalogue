<?php
namespace lbs\middlewares;

use lbs\models\Commande;
use lbs\models\User;
use lbs\controllers\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException ;
use Firebase\JWT\BeforeValidException;

/**
 * Class Token
 * @package lbs\middlewares
 */
class Token extends Controller {

    /**
     * Token de la commande
     * @param $rq
     * @param $rs
     * @param $next
     * @return mixed
     */
     public function check ($rq, $rs, $next){

        $token = $rq->getQueryParam('token', null);
        if(is_null($token))
            $token = $rq->getHeader('X-lbs-token')[0];
                
        if(empty($token)){
            $data = ['type' => 'resource',
                'meta' => ['date' =>date('d-m-Y')],
                'message' => 'token commande absent'
                ];

                return $this->jsonOutup($rs, 403, $data);
        }else{
            try{
                $route = $rq->getAttribute('route');
                $cmdId = $route->getArgument('id');
                $cmd = Commande::select()->where('id','=',$cmdId)->firstOrFail();
                 
                if($token == $cmd->token){
                    return $next($rq, $rs);
                }else{

                    $data = ['type' => 'resource',
                            'meta' => ['date' =>date('d-m-Y')],
                            'message' => 'token commande incorrecte' 
                            ];

                            return $this->jsonOutup($rs, 403, $data);

                }
    
            }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
    
                $data = [
                    'type' => 'error',
                    'error' => 404,
                    'message' => 'ressource non disponible : /Commande/ '
                ];
    
                return $this->jsonOutup($rs, 404, $data);
    
    
            }
            
        }

        

        


    }

    /**
     * Verification token JWT
     * @param $rq
     * @param $rs
     * @param $next
     * @return mixed
     */
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

    /**
     * Verification token JWT au moment de la creation de la commande
     * @param $rq
     * @param $rs
     * @param $next
     * @return mixed
     */
     public function checkJwtCreationCommande ($rq, $rs, $next){
        try {

        $secret = "mounach";
        if($rq->getHeader('Authorization') != null){
            
            $h = $rq->getHeader('Authorization')[0] ;
            $tokenstring = sscanf($h, "Bearer %s")[0] ;
            $token = JWT::decode($tokenstring, $secret, ['HS512'] );
        
        
        if(empty($token)){
            return $next($rq, $rs);
        }else{
            $jsonData = $rq->getParsedBody();
            if (!isset($jsonData['mail'])) return $response->withStatus(400);
            $mail = filter_var($jsonData['mail'], FILTER_SANITIZE_SPECIAL_CHARS);

            if( $token->umail == $mail ){

                return $next($rq, $rs);            

            }else {

                $data = [
                    'type' => 'error',
                    'error' => 404,
                    'message' => 'user jwt token invalide pour la carte demandée'
                ];

                return $this->jsonOutup($rs, 404, $data);
            }

        }
            }else{
                return $next($rq, $rs);
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

    /**
     * Verification token JWT au paiement
     * @param $rq
     * @param $rs
     * @param $next
     * @return mixed
     */
      public function checkJwtPayement ($rq, $rs, $next){
        try {

        $secret = "mounach";
        if($rq->getHeader('Authorization') != null){
            
            $h = $rq->getHeader('Authorization')[0] ;
            $tokenstring = sscanf($h, "Bearer %s")[0] ;
            $token = JWT::decode($tokenstring, $secret, ['HS512'] );
        
        
        if(empty($token)){
            return $next($rq, $rs);
        }else{
            $route = $rq->getAttribute('route');
            $cmdId = $route->getArgument('id');

            $cmd = Commande::Select()->where('id','=', $cmdId)->firstOrFail();

            if( $token->uid == $cmd->client_id ){

                return $next($rq, $rs);            

            }else {
                $data = [
                    'type' => 'error',
                    'error' => 404,
                    'message' => 'user jwt token invalide pour la carte demandée'
                ];

                return $this->jsonOutup($rs, 404, $data);
            }

        }
            }else{
                return $next($rq, $rs);
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