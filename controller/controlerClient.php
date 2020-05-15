<?php
require_once "Entity.php";
require_once "./model/UserModel.php";
class client extends Entity
{
    // Properties
    
    protected $nom ;
    protected $prenom ;
    protected $dateDeNaissance ;
    protected $ville ;
    public $comptes = [];
  

    public function __construct($c_donnees)
    {
        parent::hydrate($c_donnees);
    
    }




        // Methods
        public function getNom(){
            return $this->nom;
        }
        public function setNom($p_nom){
            $this->nom = $p_nom;
        }
        public function getPrenom(){
            return $this->prenom;
        }
        public function setPrenom($p_prenom){
            $this->prenom = $p_prenom;
        }
        public function getDateDeNaissance(){
            return $this->dateDeNaissance;
        }
        public function setDateDeNaissance($p_dateDeNaissance){
            $this->dateDeNaissance = $p_dateDeNaissance;
        }
        public function getAge(){
            $date= new DateTime();
            $date1= new DateTime ($this->getDateDeNaissance());
            $diff=date_diff($date1,$date);
            return $diff->format(" %y ans");
        }

        public function getVille(){
            return $this->ville;
        }





        public function setVille($p_ville){
            $this->ville = $p_ville;
        }

        public function addCompte($param){
            
            $s="";

            $s.=array_push($this->comptes,$param);
           
            return $s;
        }
        public function getCompts(){
            return $this->comptes;
        }
        public function getComptesInfo(){
            $s="";
            $s.= "<br> info comptes client <br> ******************** <br>";
            foreach ($this->comptes as  $value){
                
                $s.= $value->infoComptC() ;
            }
            return $s;
        }
        
    
        function infoTitulaire(){
           
            $s="";
            $s.= "<br> info client <br> ************** <br>";
            $s.= "le nom du titulaire : " . $this->getNom() ."<br>";
            $s.= "le prénom du titulaire :" . $this->getPrenom() ."<br>" ;
            $s.= "Le titulaire à " . $this->getAge() . "<br>"; 
            $s.= "Le titulaire vie à " . $this->getVille() . "<br>"; 
            $s.= $this->getComptesInfo();
            return  $s;
        }
}