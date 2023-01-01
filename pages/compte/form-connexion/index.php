<?php
    include("../../../outils/bddConnexion.php"); //connexion à la bdd

    //--------------------------------------
    // Creation compte
    if (isset($_POST['envoyerN'])) {
        $_POST['mailN'] = htmlspecialchars($_POST['mailN']);
        $_POST['mdpN'] = htmlspecialchars($_POST['mdpN']);
        $_POST['pseudoN'] = htmlspecialchars($_POST['pseudoN']);

        $reponseVerifMail = $bdd->query("SELECT COUNT(*) AS mailExistant FROM utilisateurs WHERE mail = '".$_POST['mailN']."'");
        $donneesVerifMail = $reponseVerifMail->fetch();
        if ($donneesVerifMail['mailExistant'] > 0) {
            $mailExistant = true;
        } else {
            $mailExistant = false;
        }

        if ($mailExistant == false) {
            $mdp_hache = password_hash($_POST['mdpN'], PASSWORD_DEFAULT);
            $requete = $bdd->query("INSERT INTO utilisateurs (pseudo, mail, mdp) VALUES ('".$_POST['pseudoN']."', '".$_POST['mailN']."', '".$mdp_hache."')");
        } else {
            $requete = false;
        }
    }

    if (isset($_POST['envoyerN']) AND $requete) {
        $creationCompteReussi = true;
    } elseif (isset($_POST['envoyerN']) AND $requete != true) {
        $creationCompteReussi = false;
    }

    //--------------------------------------
    // Connexion
    if (isset($_POST['envoyerC'])) {
        $_POST['mailC'] = htmlspecialchars($_POST['mailC']);
        $_POST['mdpC'] = htmlspecialchars($_POST['mdpC']);

        $reponse = $bdd->query("SELECT id, pseudo, mdp FROM utilisateurs WHERE mail = '".$_POST['mailC']."'");
        $donnees = $reponse->fetch();
        $mdpCorrecte = password_verify($_POST['mdpC'], $donnees['mdp']);

        if(!$donnees OR !$mdpCorrecte){
            $connexionReussi = false;
        } else {
            $connexionReussi = true;
            session_start();
                $_SESSION['id'] = $donnees['id'];
                $_SESSION['pseudo'] = $donnees['pseudo'];
                echo '<meta http-equiv="refresh" content="0; URL=/devoirs/pages/compte/" />';
            }
        }

?>


<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="style.css">
  <link rel="icon" type="image/png" href="/devoirs/images/favicon.png" />
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/30b89b8fe6.js" crossorigin="anonymous"></script>
</head>

<body>

    <form method="POST">
        <h2>Je me connecte</h2>
        <label for="mailC">Adresse e-mail</label>
        <input type="email" name="mailC" placeholder="monadresse@monfournisseur.com" required maxlength="150">
        <label for="mdpC">Mot de passe</label>
        <input type="password" name="mdpC" placeholder="* * * * * * *" required maxlength="110">
        <div class="submitBox"><input type="submit" name="envoyerC" value="Envoyer"></div>
        <?php
            if (isset($connexionReussi)) {
                if ($connexionReussi == false) { ?>
                    <p style="color: red;"><b>Adresse e-mail ou mot de passe incorrect </b>😖</p>
                <?php }
            }
        ?>
    </form>
    <div id="separation"></div>
    <form method="POST">
        <h2>Je créé un compte</h2>
        <label for="mailN">Adresse e-mail</label>
        <input type="email" name="mailN" placeholder="monadresse@monfournisseur.com" required maxlength="150">
        <label for="pseudoN">Pseudo</label>
        <input type="text" name="pseudoN" placeholder="BiboupDu89" onkeydown="if(event.keyCode==32) return false;" required autocomplete="off" maxlength="30">
        <label for="mdpN">Mot de passe</label>
        <input type="password" name="mdpN" placeholder="* * * * * * *" required autocomplete="off" maxlength="100">
        <div class="submitBox"><input type="submit" name="envoyerN" value="Envoyer"></div>
        <?php
            
        ?>
    </form>
    <div id="separation"></div>
    <div id="separation"></div>
    <div id="separation"></div>

    <?php
    if (isset($creationCompteReussi) AND $creationCompteReussi == true) {?>
        <div id="popup">
          <div id="card">
            <h2 style="color: limegreen;">Votre compte a bien été créé ! 😀</h2>
            <p>vous pouvez activer votre compte en vous connectant ci-dessus</p>
            <form method="POST">
                <button type="submit">OK</button>
            </form>
          </div>
        </div>
      <?php } elseif (isset($creationCompteReussi) AND $creationCompteReussi == false AND $mailExistant == true) { ?>
        <div id="popup">
          <div id="card">
            <h2 style="color: red;">Cette adresse e-mail existe déjà ! 😮</h2>
            <p>Veuillez vous connecter ci-dessus</p>
            <form method="POST">
                <button type="submit">OK</button>
            </form>
          </div>
        </div>
      <?php } elseif (isset($creationCompteReussi)) { ?>
        <div id="popup">
          <div id="card">
            <h2 style="color: red;">Une erreur est survenue 🤔</h2>
            <p>Veuillez réessayer prochainement</p>
            <form method="POST">
                <button type="submit">OK</button>
            </form>
          </div>
        </div>
      <?php }
    ?>

</body>

<nav>
    <div id="block">
        <a href="../../../" id="home">
            <div class="img" ><img src="../../../images/homeBordure.svg" style="height: 22px; margin-top: 5px;"></div>
        </a>
        <a href="../../../pages/mes-devoirs" id="mesDevoirs">
            <div class="img" ><i class="far fa-folder"></i></div>
        </a>

        <a href="../../../pages/nouveau-devoir" id="nouveauDevoir">
            <div class="img" >+</div>
        </a>

        <a href="../../../pages/mes-likes" id="likes">
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