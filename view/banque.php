

<?php
require_once ('client.php');
require_once ('Compts.php');

$arrt1= [
    'nom' => 'Rioux',
    'prenom'=>'Simon',
    'dateDeNaissance'=>'16-03-1996',
    'ville'=>'Labroque'
];


$t1 = new client($arrt1);// créer le client


$arrc11= [
    'libelle' => 'compte courent',
    'soldeInitial'=>100,
    'deviseMonétaire'=>'euros',
    'numero'=>1,
    'client'=> $t1
];


$arrc12= [
    'libelle' => 'Livret A',
    'soldeInitial'=>100,
    'deviseMonétaire'=>'euros',
    'numero'=>2,
    'client'=> $t1
];

$c11 = new Compte($arrc11);//créer le 1er compte 
$c12 = new Compte($arrc12);// créer le second compte
$t1->addCompte($c11); //attribut un 1er comptes au client
$t1->addCompte($c12); //attribut un 2em comptes au client


$arrt2= [
    'nom' => 'astier',
    'prenom'=>'alexendre',
    'dateDeNaissance'=>'16-06-1974',
    'ville'=>'Lyon'
];

$t2 = new client($arrt2);
$arrc21= [
    'libelle' => 'compte courent',
    'soldeInitial'=>100,
    'deviseMonétaire'=>'euros',
    'numero'=>1,
    'client'=> $t2
];
$arrc22= [
    'libelle' => 'Livret A',
    'soldeInitial'=>100,
    'deviseMonétaire'=>'euros',
    'numero'=>2,
    'client'=> $t2
];
$c21 = new Compte($arrc21);
$c22 = new Compte($arrc22);
$t2->addCompte($c21);
$t2->addCompte($c22);



echo $c11->infoCompt();
$c11->créditer(100);
echo $c11->infoCompt();
echo $c12->infoCompt();
$c11->virement(100,$c12);
echo $c11->infoCompt();
echo $c12->infoCompt();

echo $t1->infoTitulaire();
?>