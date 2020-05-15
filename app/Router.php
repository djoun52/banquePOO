<?php
    //cette classe prendra en charge toute requète GET arrivante !
    abstract class Router{
        
        const DEFAULT_CTRL = "SecurityController";//controller par défaut
       
        public static function csrfProtection($csrf){
            //si le hash dans le formulaire n'est pas comparable à celui qui a été obtenu grâce à la clé de session
            //(voir index.php)
            try{
                if(isset($_POST['csrf_token']) && !hash_equals($_POST['csrf_token'], $csrf)){
                    //le router lance une redirection en passant par un logout éventuel
                    throw new Exception("<b>WARNING</b>: INVALID CSRF TOKEN");
                }
            }
            catch(Exception $e){
                echo $e->getMessage();
                die();
            }
        }
        
        public static function handleRequest($params){ //où $params = $_GET
            
            //le nom de la classe à appeller (par défaut)
            $ctrlname = self::DEFAULT_CTRL;
            //on en obtient le fichier où elle se trouve
            $ctrlfile = CTRL_PATH.$ctrlname.".php";
            //on appelle le fichier
            require $ctrlfile;
            //et on instancie le contrôleur
            $controller = new $ctrlname();
            
            $method = "index";//méthode à appeller par défaut
            //et si la méthode est dans les paramètres de l'URL ET SI C'EST UNE METHODE EXISTANTE DANS LE CONTROLEUR
            if(isset($params['action']) && method_exists($controller, $params['action'])){
                $method = $params['action'];
            }
            //on retourne enfin le résultat de la méthode du contrôleur
            return $controller->$method();
        }
    }
  
    
    
    