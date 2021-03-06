<?php

namespace App;

   use App\Request;
   use App\Session;

   final class App{
        static protected $action;   
        static protected $req;

        private static function env(){
            $ipAddress=gethostbyname($_SERVER['SERVER_NAME']);
            if($ipAddress=='127.0.0.1'){
                return 'dev';
            }else{
                return 'pro';
            }
        }
        private static function loadConf(){
            $file="config.json";
            $jsonStr=file_get_contents($file);
            $arrayJson=json_decode($jsonStr);
            return $arrayJson;
        }
        static function init(){
            $config=self::loadConf();
            $strconf='conf_'.self::env();   
            $conf=(array)$config->$strconf;           
            return $conf;

        }
        public static function run(){
            $session=new Session();
            $routes=self::getRoutes();
            self::$req=new Request;
            $controller=self::$req->getController();       
            self::$action=self::$req->getAction();
                    
            self::dispatch($controller,$routes,$session);
        }
        
        private static function dispatch($controller,$routes,$session):void 
        {            
            try{
                if(in_array($controller,$routes)){
                   $nameController='\\App\Controllers\\'.ucfirst($controller).'Controller';
                   $objContr=new $nameController(self::$req,$session);                 
                   if (is_callable([$objContr,self::$action])){
                       call_user_func([$objContr,self::$action]);
                   }else{
                       call_user_func([$objContr,'error']);
                   }

               }else{
                    throw new \Exception("Ruta no disponible");
                }
           }catch(\Exception $e){
               die($e->getMessage());
           }
        }
        static function getRoutes(){
            $dir=__DIR__.'/Controllers';
            $handle=opendir($dir);
            while(false !=($entry=readdir($handle))){
               if($entry!="." && $entry!=".."){
                   $routes[]=strtolower(substr($entry,0,-14));
               }              
            } 
            return $routes;
        }
   }