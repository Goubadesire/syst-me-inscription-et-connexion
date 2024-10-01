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
    echo 'echèc lors de la connexion a votre base de donnée';
}

// on vérifie d'abord si l'utilisateur a appuyer sur s'insrcire
if(isset($_POST['inscrire'])){
    if(!empty($_POST['nom']) AND !empty(['prenom']) AND !empty(['email']) AND !empty(['mdp'])){ // on vérifie si tout les champs on été renseigné
        // sécurisation du formulaire
        $nom = '';
        $prenom = '';
        $email = '';
        $mdp = '';
    
        function securisation($donnees){
            $donnees = trim($donnees);
            $donnees = strip_tags($donnees);
            $donnees = stripslashes($donnees);
            return $donnees;
        }
    
        $nom = securisation($_POST['nom']);
        $prenom = securisation($_POST['prenom']);
        $email = securisation($_POST['email']);
        $mdp = securisation($_POST['mdp']);
        
        
            //on vérifie ici que le mot de pass est suppérieur a 7
            if(strlen($mdp) < 7){ // ce message sera déclanché lorque le mdp sera inférieur a 7
                $message = 'Votre mot de pass doit etre suppérieur a 7 caractères';
            }
            elseif(strlen($nom) > 50 || strlen($prenom) > 50){
                $message = 'votre nom ou votre prénom est trop long';
            }
            else{ // si tout les champs on la bonne taille on va maintenant l'inscrire
                // ici on va vérifier que l'email saisit est unique et n'exite pas deja dans notre base de donnée
                $testmail = $connexion->prepare("SELECT * FROM utilisateurs WHERE email = ?");
                $testmail->execute(array($email));
                // cette variable va compter les address mail associé a notre address particulière qu'on vient de renseigné
                $controlmail = $testmail->rowCount();
                if($controlmail == 0){ // on vérifie maintenant si l'email saisit n'existe pas deja dans notre base de donnée
                // on insert maintenant les données dans la base de donnée 
                $insertion = $connexion->prepare("INSERT INTO utilisateurs(nom,prenom,email,mdp) VALUES(?,?,?,?)");
                $insertion->execute(array($nom,$prenom,$email,$mdp));
                $message = 'Votre compte a été crée avec succès vous pouvez a présent appuis sur connexion pour vous connecter a votre compte';
                }
                else{ // sinon si l'email existe deja 
                    $message = 'Désolé cette address a déjà un compte.';
                }
    }
}    
}

else{ // sinon
    echo $message = 'vueillez remplir tout les champs';
}



?>
<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/all.min.css">
    <title>Formulaire d'inscription</title>
</head>

<body class="bg-light">
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top">
                <h2 class="text-center">Inscription</h2>
                <p class="text-center text-muted lead">Simple et rapide</p>
                <form action="./inscription.php" method="post">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input type="text" name="nom" class="form-control" placeholder="Nom">

                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input type="text" name="prenom" class="form-control" placeholder="Prénom">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="E-mail">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" name="mdp" class="form-control" placeholder="Mot de passe">

                    </div>

                    <div class="d-grid">
                        <button type="buton" name="inscrire" class="btn btn-success">S'inscrire</button>
                        <p class="text-center text-muted">
                            <i style="color: red">
                                <?php 
                                    if(isset($message)){ // ici on appel la variable message deja crée plus en haut
                                        echo $message . '<br/>';
                                    }
                                ?></i>
                        </p>
                        <p class="text-center text-muted">
                            En cliquant sur S'inscrire, vous acceptez nos <a href="">condition générales</a>, notre
                            <a href="">politique de confidentialité</a> et notre <a href="">politique
                                d'utilisation</a>
                            des cookies.
                        </p>
                        <p class="text-center text-muted">
                            Avez vous déjà un compte ? <a href="./connexion.php">Connexion</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
<script src="./js/bootstrap.bundle.js"></script>