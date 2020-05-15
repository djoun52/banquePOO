<?php
require_once "../model/UserModel.php";
require_once "controlerCompts.php";
require_once "controlerClient.php";

class SecurityController
{

    private $model;

    public function __construct()
    {
        //on instancie le modèle directement
        $this->model = new UserModel();
    }

    public function index()
    {

        //on servira home si pas d'utilisateur connecté
        $view = "index.php";
        if (isset($_SESSION['user'])) {
            //on servira welcome si connecté
            $view = "newGame.php";
        }

        return [
            "view" => $view
        ];
    }

    public function connexion()
    {
        //si on arrive ici avec un formulaire rempli
        if (!empty($_POST['u_email']) && !empty($_POST['u_password'])) {

            $email = trim(filter_input(INPUT_POST, 'u_email', FILTER_SANITIZE_URL));
            $password = filter_input(
                INPUT_POST,
                'u_password',
                FILTER_VALIDATE_REGEXP,
                array("options" => array("regexp" => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/"))
            );

            //on demande au modèle de nous récupérer un possible utilisateur portant ce nom
            $result = $this->model->getUsersByEmail($email);
            //si $result n'est pas false
            if ($result !== false) {
                if (password_verify($password, $result['password'])) {
                    // var_dump($result);
                    $_SESSION['user'] = new client($result);

                    
                    var_dump($_SESSION['user']);
                    // unset($_SESSION['error_msg']);
                    $_SESSION['error_msg'] = '';
                    $_SESSION['nbErreurMsg'] = 0;
                    $_SESSION["init"] = 0;
                    if ($_POST['auto']) {
                        setcookie("user", $result['secret'], time() + (86400 * 30), "/");
                    }
                    header('Location: ?action=index');
                    die();
                } else {
                    // var_dump($result);

                    $_SESSION['error_msg'] = '<p class="error">utilisateur ou mots de passe incorect</p>';
                    $_SESSION['nbErreurMsg']++;
                    header('Location: ?action=connexion');
                    die();
                }
            } else {
                //utlisateur nexiste pas 
                $_SESSION['error_msg'] = "<p class='error'>utilisateur ou mots de passe incorect</p>";
                $_SESSION['nbErreurMsg']++;
                header('Location: ?action=connexion');
                die();
            }
        } else {
            // remplir les champs
            $_SESSION['error_msg'] = '<p class="error">utilisateur ou mots de passe incorect</p>';
            $_SESSION['nbErreurMsg']++;
            header('Location: ?action=connexion');
            die();
        }

        return [
            "view" => "connexion.php"
        ];
    }

    public function inscription()
    {

        $passworconfirm = trim(filter_input(INPUT_POST, 'u_confirmer_password', FILTER_SANITIZE_URL));
        $email = trim(filter_input(INPUT_POST, 'u_email', FILTER_SANITIZE_URL));
        $prenom = trim(filter_input(INPUT_POST, 'u_prenom', FILTER_SANITIZE_URL));
        $nom = trim(filter_input(INPUT_POST, 'u_nom', FILTER_SANITIZE_URL));
        $ville = trim(filter_input(INPUT_POST, 'u_ville', FILTER_SANITIZE_URL));
        $dateDeNaissance = trim(filter_input(INPUT_POST, 'u_dateDeNaissance', FILTER_SANITIZE_URL));

        $password = filter_input(
            INPUT_POST,
            'u_password',
            FILTER_VALIDATE_REGEXP,
            array("options" => array("regexp" => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/"))
        );
        if (!empty($_POST)) {
            $useremail = $_POST['u_email'];

            if (!in_array("", $_POST)) {
                if (!$this->model->getUsersByEmail($useremail)) {
                    if ($password == true) {
                        if ($password == $passworconfirm) {
                            $hashage = password_hash($_POST['u_password'], PASSWORD_ARGON2I);


                            $bytes = random_bytes(255);
                            $secret = password_hash($bytes, PASSWORD_ARGON2I);

                            $this->model->addUser($nom, $prenom,$email, $hashage,$ville,$dateDeNaissance, $secret);

                            $_SESSION['error_msg'] = '';
                            $_SESSION['nbErreurMsg'] = 0;
                            header('Location: ?action=connexion');
                            die();
                        } else {
                            $_SESSION['error_msg'] = '<p class="error"> les mots de passe ne coresponde pas</p>';
                            $_SESSION['nbErreurMsg']++;
                            header('Location: ?action=inscription');
                            die();
                        }
                    } else {
                        $_SESSION['error_msg'] = '<p class="error"> les mots de passe n"est pas comforme</p>';
                        $_SESSION['nbErreurMsg']++;
                        header('Location: ?action=inscription');
                        die();
                    }
                } else {
                    $_SESSION['error_msg'] = '<p class="error"> utilisateur existe</p>';
                    $_SESSION['nbErreurMsg']++;
                    header('Location: ?action=inscription');
                    die();
                }
            } else {
                $_SESSION['error_msg'] = '<p class="error">Veuillez remplir les champs </p>';
                $_SESSION['nbErreurMsg']++;
                header('Location: ?action=inscription');
                die();
            }


            return [
                "view" => "inscription.php"
            ];
        }
    }

    
    public function deconnexion()
    {

        session_destroy(); //on détruit l'user en session
        setcookie("user", $_SESSION['secret'], time() - 50, "/");
        header("Location:?action=index"); //et on renvoit vers l'accueil !
        die();
    }


    public function goToIdenti()
    {

        unset($_SESSION['error_msg']);
        unset($_SESSION['nbErreurMsg']);
        header("Location: ?action=connexion.php");
        die();
    }

    public function gotoinscrip()
    {

        unset($_SESSION['error_msg']);
        unset($_SESSION['nbErreurMsg']);
        header("Location: ?action=inscription.php");
        die();
    }
}
