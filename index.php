<?php
 //on charge le routeur
 require "./app/Router.php";

 //ici, on définit dans des constantes globales les différents chemins des fichiers MVC
 define("CTRL_PATH", "./controller/");//les contrôleurs sont là
 define("MODEL_PATH", "./model/");    //les modèles sont là
 define("VIEW_PATH", "./view/");      //les vues sont là

 session_start();//un ptit démarrage de session
 
 //si la clé csrf n'est pas en session
 if(!isset($_SESSION['key'])){
     //on la met !
     $_SESSION['key'] = bin2hex(random_bytes(24));
 }
 /*
     et on créé un hash csrf à partir de la clé en session et d'une chaîne secrète
     sans la clé en session, le hash sera invérifiable
     ce hash sera injecté dans TOUS les formulaires !!
 */
 $csrf = hash_hmac('sha256', 'secret_key', $_SESSION['key']);
 
 //on lance la vérification csrf (au cas où un formulaire a été validé)
 Router::csrfProtection($csrf);
 //on demande au routeur de prendre en charge la requète HTTP qui nous amène ici
 //et on récupère le résultat du contrôleur qui aura été appelé
 $result = Router::handleRequest($_GET);
 


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="wrapper">
 


    </div>

    <h1>Bienvenue sur le site de La Banque française</h1>



</body>
</html>