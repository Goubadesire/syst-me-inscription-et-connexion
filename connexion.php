<?php  
// connexion a la base de donnée
$serveur = 'localhost';
$user = 'root';
$password = '';
try{
    $connexion = new PDO("mysql:host=$serveur;dbname=vibe",$user,$password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    //echo 'connexion établis';
}
catch(PDOException $e){
    echo 'echès lors de la connexion a votre base de donnée';
}

// vérification des informations
// on vérifie si le bouton connexion  été activer par l'utilisateur
if(isset($_POST['connexion'])){
    // on va vérifier si tout les champs son renseigner par l'utilisateur
    if(!empty($_POST['email']) AND !empty(['mdp'])){
        // on va sécuriser le formulaire
        $email = '';
        $mdp = '';
        
        function securisation($donnees){
            $donnees = trim($donnees);
            $donnees = strip_tags($donnees);
            $donnees = stripslashes($donnees);
            return $donnees;            
        }

        $email = securisation($_POST['email']);
        $mdp = securisation($_POST['mdp']);

        // on prepare notre requette
        $req = $connexion->prepare("SELECT * FROM utilisateurs WHERE email = ? AND mdp = ?");
        $req->execute(array($email,$mdp)); 
        $cpt = $req->rowCount(); // ici cette rquette compte les différent email et password de notre bd

        if($cpt==1){ // si le bon email et password a été trouvé
            header('location: affichage.php');
            exit();
        }
        else{
            $message = 'Aucun compte ne correspond aux information renseigné';
        }
    }
    else {
        echo $message = 'Vueillez remplir tout les champs';
    }

}



?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/all.min.css">
    <title>Connexion</title>
</head>

<body class="bg-light">
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top">
                <h2 class="text-center">Connexion</h2>
                <p class="text-center text-muted lead">Se connecté a www.cartel.com </p>
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="E-mail">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" name="mdp" class="form-control" placeholder="Mot de passe">
                    </div>

                    <div class="d-grid">
                        <button type="buton" name="connexion" class="btn btn-success">Se connecter</button>
                        <p class="text-center text-muted">
                            <i style="color:red">
                                <?php
                                        if(isset($message)){
                                            echo $message . '<br/>';
                                        }
                                    ?>
                            </i>
                            N'avez vous pas un compte ? <a href="./inscription.php">S'inscrire</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
<script src="./js/bootstrap.bundle.js"></script>