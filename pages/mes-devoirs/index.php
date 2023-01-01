<?php session_start(); ?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Mes devoirs</title>
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
        $reponseDevoirs = $bdd->query("SELECT * FROM liste_devoirs WHERE id_createur = '".$_SESSION['id']."' ORDER BY isResolu, id DESC LIMIT 40");
    ?>

<body>
    <div id="titrePageBox"><h1>Mes devoirs 📁</h1></div>

    <?php

    while ($donneesDevoirs = $reponseDevoirs->fetch()) {
      if ($donneesDevoirs['isResolu'] == 1) { $resolu = true; } else { $resolu = false; }

      //on met l'émoji devant la matiere :
      if ($donneesDevoirs["matiere"] == "Français") {
      $donneesDevoirs["matiere"] = "📚 Français";
    } elseif ($donneesDevoirs["matiere"] == "Maths") {
      $donneesDevoirs["matiere"] = "📐 Maths";
    } elseif ($donneesDevoirs["matiere"] == "Anglais") {
      $donneesDevoirs["matiere"] = "💂 Anglais";
    } elseif ($donneesDevoirs["matiere"] == "Histoire") {
      $donneesDevoirs["matiere"] = "👑 Histoire";
    } elseif ($donneesDevoirs["matiere"] == "Geographie") {
      $donneesDevoirs["matiere"] = "🗺️ Geographie";
    } elseif ($donneesDevoirs["matiere"] == "Espagnol") {
      $donneesDevoirs["matiere"] = "💃 Espagnol";
    } elseif ($donneesDevoirs["matiere"] == "Allemand") {
      $donneesDevoirs["matiere"] = "🥨 Allemand";
    } elseif ($donneesDevoirs["matiere"] == "Physique-Chimie") {
      $donneesDevoirs["matiere"] = "🔬 Physique-Chimie";
    } elseif ($donneesDevoirs["matiere"] == "SVT") {
      $donneesDevoirs["matiere"] = "🌋 SVT";
    } elseif ($donneesDevoirs["matiere"] == "Informatique") {
      $donneesDevoirs["matiere"] = "💻 Informatique";
    } elseif ($donneesDevoirs["matiere"] == "Autre") {
      $donneesDevoirs["matiere"] = "📁 Autre";
    }
      ?>

        <a class="card">
            <p class="titre"><?php echo $donneesDevoirs['titre'] ?></p>
            <div class="matiereClasseBox">
                <p class="matiere"><?php echo $donneesDevoirs['matiere']; ?></p>
                <p class="classe"><?php echo $donneesDevoirs['classe']; ?></p>
                <?php if ($resolu == true) { echo '<p class="resolu">Résolu</p>'; }?>
            </div>
            <div class="bottom">
                <?php
                $reponseNbrReponses = $bdd->query('SELECT COUNT("id") AS nbrReponses FROM reponses WHERE id_devoir = "'.$donneesDevoirs['id'].'"');
                $donneeNbrReponses = $reponseNbrReponses->fetch();

                    if ($donneeNbrReponses['nbrReponses'] == 0) { ?>
                    <div class="nbrReponsesBox nul">
                        <i class="far fa-comments"></i>
                        <p><?php echo $donneeNbrReponses['nbrReponses']; ?></p>
                    </div>
                <?php } elseif ($donneeNbrReponses['nbrReponses'] >= 1) { ?>
                    <div class="nbrReponsesBox">
                        <i class="far fa-comments"></i>
                        <p><?php echo $donneeNbrReponses['nbrReponses']; ?></p>
                    </div>
                <?php } ?>
                <div class="separation"></div>
                    <div class="dateDevoir">
                        <?php echo substr($donneesDevoirs['date_creation'], 8, 2)."/".substr($donneesDevoirs['date_creation'], 5, 2)."/".substr($donneesDevoirs['date_creation'], 0, 4); ?>
                </div>
                <form method="GET" class="cardForm" action="../../pages/page-devoir">
                    <input type="hidden" name="idNumber" value="<?php echo $donneesDevoirs['id'] ?>">
                    <button type="submit"><i class="fas fa-arrow-right"></i></button>
                </form>
            </div>
        </a>

    <?php }

    ?>

</body>

<nav>
    <div id="block">
        <a href="../../" id="home">
          <div class="img" ><img src="../../images/homeBordure.svg" style="height: 22px; margin-top: 5px;"></div>
        </a>
        <a href="../../pages/mes-devoirs" id="mesDevoirs">
              <div class="img" ><i class="fas fa-folder"></i></div>
              <div class="point"></div>
          </a>

        <a href="../../pages/nouveau-devoir" id="nouveauDevoir">
          <div class="img" >+</div>
        </a>

        <a href="../../pages/mes-likes" id="likes">
          <div class="img" ><i class="far fa-heart"></i></div>
        </a>
        <a href="../../pages/compte" id="compte">
          <div class="img" ><i class="far fa-user"></i></div>
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
    display: flex;
    flex-direction: column;
    align-items: center;
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

<?php } ?>
</html>