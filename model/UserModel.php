<?php
    require_once("connectPOO.php");

    class UserModel extends DAO{

        public function __construct(){
            //la propriété connexion contiendra une instance de PDO toute fraîche
            //grâce au DAO
            parent::connect();
        }

        public function getUsersByEmail($email){
            try{
                $sql = "SELECT * FROM users WHERE email = :email";

            //préparation de la requète dans le serveur       
                $stmt = self::$connexion->prepare($sql);
            //injection des paramètres
                $email = strtolower($email);
                $stmt->bindParam("email", $email); // requete vers database

            //execution
            $result = $stmt->fetch();
            //on retourne l'utilisateur en base de données
                return $result;
            }
            catch(Exception $e){
                return $e->getMessage();
                die();
            }
            
        }

        public function addUser($nom,$prenom,$email,$password,$ville,$dateDeNaissance, $secret){
            try{
                $sql = "INSERT INTO users (nom, prenom, email, password,ville,dateDeNaissance, secret) VALUES (?,?,?,?,?,?,?)";

            //préparation de la requète dans le serveur       
                $stmt = self::$connexion->prepare($sql);
            
                $stmt->execute(array($nom, $prenom,$email, $password,$ville,$dateDeNaissance, $secret));
            //execution
                return $stmt->execute();
                
            }
            catch(Exception $e){
                return $e->getMessage();
                die();
            }
            
        }
        public function addcompt($param){
            try{
                $sql = "INSERT INTO compts (libelle, soldeInitial, deviseMonétaire, numero) VALUES (?,?,?,?)";

            //préparation de la requète dans le serveur       
                $stmt = self::$connexion->prepare($sql);
                foreach ($param as $key => $value) {
                    switch ($key) {
                        case 'libelle':
                            $libelle=$value;
                            break;
                        case 'soldeInitial':
                            $soldeInitial=$value;
                            break;
                        case 'deviseMonétaire':
                            $deviseMonétaire=$value;
                            break;
                        case 'numero':
                            $numero=$value;
                            break;
                        
                    }
                }
                $stmt->execute(array($libelle, $soldeInitial,$deviseMonétaire, $numero));
            //execution
                return $stmt->execute();
                
            }
            catch(Exception $e){
                return $e->getMessage();
                die();
            }
            
        }


        public function getUsersBySecret($param){
            try {
                $sql = 'SELECT * FROM users WHERE secret= :secret';


                //préparation de la requète dans le serveur       
                $stmt = self::$connexion->prepare($sql);

                //injection des paramètres
                $stmt->bindParam("secret", $param);

                 //execution
                $stmt->execute();
                $result = $stmt->fetch();
                if ($result !== false) {
                    $_SESSION['user'] = $result;
                    $_SESSION['error_msg'] = '';
                    // var_dump($_COOKIE);
                    header('Location: vue/newGame.php');
                    die();
                }
            } catch (PDOException $error) {
                $now = new DateTime("", new DateTimeZone('Europe/Paris'));
                $now = $now->format("d-M-Y H:i:s");
                $msg = $now . " - ERREUR BDD : " . $error->getMessage() . PHP_EOL;
                file_put_contents('log_cookie.txt', $msg, FILE_APPEND);
                $_SESSION['error_msg'] = "Hacker";
                // var_dump($_COOKIE);
        
                // header('Location: index.php');
                die();
            }
        }

    }