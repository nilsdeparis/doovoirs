<?php session_start(); ?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon compte</title>
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="style.css">
  <link rel="icon" type="image/png" href="/devoirs/images/favicon.png" />
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/30b89b8fe6.js" crossorigin="anonymous"></script>
</head>

<?php
    if(isset($_SESSION['id'])){
        $autorise = true;
    } else {
        $autorise = false;
        echo '<meta http-equiv="refresh" content="0; URL=/devoirs/pages/compte/form-connexion/" />';
    }

if ($autorise == true) {
    include("../../outils/bddConnexion.php"); //connexion à la bdd


    // Modification pseudo
    if (isset($_POST['nouveauPseudoBtn'])) {
        $requetePseudo = $bdd->query("UPDATE utilisateurs SET pseudo = '".$_POST['nouveauPseudo']."' WHERE id = '".$_SESSION['id']."'");
    }

    // Modification mot de passe
    if (isset($_POST['nouveauMdp'])) {
        $mdp_hache = password_hash($_POST['nouveauMdp'], PASSWORD_DEFAULT);

        $requeteMdp = $bdd->query("UPDATE utilisateurs SET mdp = '".$mdp_hache."' WHERE id = '".$_SESSION['id']."'");
    }

    if (isset($_POST['nouveauPseudo']) AND $requetePseudo) {
            $nouveauPseudoReussi = true;
        } elseif (isset($_POST['nouveauPseudo']) AND $requetePseudo != true) {
            $nouveauPseudoReussi = false;
        }

    if (isset($_POST['nouveauMdp']) AND $requeteMdp) {
            $nouveauMdpReussi = true;
        } elseif (isset($_POST['nouveauMdp']) AND $requeteMdp != true) {
            $nouveauMdpReussi = false;
        }

?>

<body>
    <h1>Mon Compte</h1>

    <div id="niveauBox">
        <p id="titre">Mon niveau</p>
        <p id="xp">Bientôt<br>disponible</p>
        <div id="indicateurNiv">
            <p>?</p>
        </div>
    </div>

    <h2>MES STATS</h2>
    <?php
    //Nbr reponses ecrites
    $reponseNbrReponses = $bdd->query('SELECT COUNT(*) AS nbr FROM reponses WHERE id_createur = "'.$_SESSION['id'].'"');
    $nbrReponses = $reponseNbrReponses->fetch();

    //Nbr sujets resolus
    $reponseNbrSujetsResolus = $bdd->query('SELECT COUNT(*) AS nbr FROM reponses WHERE isSolution = 1 AND id_createur = "'.$_SESSION['id'].'"');
    $nbrSujetsResolus = $reponseNbrSujetsResolus->fetch();
?>

    <div class="statBox">
        <div class="imgBox" style="background-color: #DDFFF5;"><i class="fas fa-comments" style="color: #00EB9D;"></i></div>
        <div class="txtBox">
            <p class="reponse" style="color: #00EB9D;"><?php echo $nbrReponses['nbr']; ?></p>
            <p class="sous-titre">Réponses envoyés</p>
        </div>
    </div>
    <div class="statBox">
        <div class="imgBox" style="background-color: #DDF4FF;"><i class="fas fa-clipboard-check" style="color: #27B9FF;"></i></div>
        <div class="txtBox">
            <p class="reponse" style="color: #27B9FF;"><?php echo $nbrSujetsResolus['nbr']; ?></p>
            <p class="sous-titre">Sujets résolus</p>
        </div>
    </div>

    <h2>MODIFIER</h2>
    <form method="POST">
        <label>Mon pseudo</label><br>
        <div>
            <input type="text" class="input" name="nouveauPseudo" placeholder="Nouveau pseudo" required autocomplete="off" maxlength="30">
            <input type="submit" name="nouveauPseudoBtn" value="Modifier">
        </div>
        <?php
        if (isset($nouveauPseudoReussi) AND $nouveauPseudoReussi == true) {
            echo "Le nouveau pseudo a été ajouté 😉";
        } elseif (isset($nouveauPseudoReussi) AND $nouveauPseudoReussi == false) {
            echo "Une erreur est survenue, veuillez réessayer 😣";
        }
        ?>
    </form>
    <form method="POST">
        <label>Mon mot de passe</label><br>
        <div>
            <input type="password" class="input" name="nouveauMdp" placeholder="Nouveau mot de passe" required autocomplete="off" maxlength="100">
            <input type="submit" name="nouveauMdpBtn" value="Modifier">
        </div>
        <?php
        if (isset($nouveauMdpReussi) AND $nouveauMdpReussi == true) {
            echo "Le nouveau mot de passe a été ajouté 😉";
        } elseif (isset($nouveauMdpReussi) AND $nouveauMdpReussi == false) {
            echo "Une erreur est survenue, veuillez réessayer 😣";
        }
        ?>
    </form>

    <h2>AUTRE</h2>
    <form method="POST" id="deconnexionBox">
        <input type="submit" id="deconnexion" name="finSession" value="Me Déconnecter">
    </form>
    <?php
    if (isset($_POST['finSession'])) {
        session_destroy();
        echo '<meta http-equiv="refresh" content="0; URL=/devoirs/pages/compte/form-connexion/" />';
    }
    ?>

</body>

<?php } ?>

<nav>
    <div id="block">
        <a href="../../" id="home">
            <div class="img" ><img src="../../images/homeBordure.svg" style="height: 22px; margin-top: 5px;"></div>
        </a>
        <a href="../../pages/mes-devoirs" id="mesDevoirs">
            <div class="img" ><i class="far fa-folder"></i></div>
        </a>

        <a href="../../pages/nouveau-devoir" id="nouveauDevoir">
            <div class="img" >+</div>
        </a>

        <a href="../../pages/mes-likes" id="likes">
            <div class="img" ><i class="far fa-heart"></i></div>
        </a>
        <a href="#" id="compte">
            <div class="img" ><i class="fas fa-user"></i></div>
            <div class="point"></div>
        </a>
    </div>
</nav>
<style type="text/css">
    nav{
    position: fixed;
    bottom: 0px;
    left: 0px;
    width: 100%;
    z-index: 1000;
}

nav #block{
    background-color: white;
    height: 50px;
    box-shadow: 0px -3px 13px rgba(0,0,0,0.15);
    border-radius: 10px 10px 0px 0px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-around;
    padding-bottom: 20px;
}

nav a{
    color: #0099FF;
    text-decoration: none;
    margin-top: -2px;
}

nav a {
    font-size: 22px;
    font-family: montserrat;
}

nav #nouveauDevoir{
    position: absolute;
    top: -30px;
    background: rgb(36,175,254);
    background: linear-gradient(145deg, rgba(36,175,254,1) 0%, rgba(5,130,255,1) 100%);
    height: 65px;
    width: 65px;
    border-radius: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0px 3px 20px rgba(0,168,255,0.4);
    font-size: 45px;
}

nav #compte {
    display: flex;
    flex-direction: column;
    align-items: center;
}
nav .point{
    height: 5px;
    width: 5px;
    border-radius: 50px;
    background-color: #0099FF;
}

nav #mesDevoirs{
    margin-right: 40px;
}
nav #likes{
    margin-left: 40px;
}

@media (min-width: 430px){
    nav{
        display: flex;
        justify-content: center;
    }

    nav #block{
        width: 450px;
        box-shadow: 0px -3px 20px rgba(0,0,0,0.25);
        padding-bottom: 0;
        height: 60px;
    }

    nav a {
        font-size: 24px;
    }

    nav #home img{
        height: 24px;
    }
}
</style>

</html>