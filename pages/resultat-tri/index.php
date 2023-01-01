<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Aide aux devoirs en ligne</title>
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
  include("../../outils/bddConnexion.php"); //connexion à la bdd


  //début pagination
    $messagesParPage=10; //Nous allons afficher 5 messages par page.
     
    //Une connexion SQL doit être ouverte avant cette ligne...
    $retour_total=$bdd->query('SELECT COUNT(*) AS total FROM liste_devoirs WHERE date_fin > "'.date("Y-m-d").'"'); //Nous récupérons le contenu de la requête dans $retour_total
    $donnees_total=$retour_total->fetch(); //On range retour sous la forme d'un tableau.
    $total=$donnees_total['total']; //On récupère le total pour le placer dans la variable $total.
     
    //Nous allons maintenant compter le nombre de pages.
    $nombreDePages=ceil($total/$messagesParPage);
     
    if(isset($_GET['page'])){

         $pageActuelle=htmlspecialchars($_GET['page']);
     
         if($pageActuelle>$nombreDePages) {
              $pageActuelle=$nombreDePages;
         }
         if($pageActuelle<1) {
              $pageActuelle=1;
         }
    }
    else // Sinon
    {
         $pageActuelle=1; // La page actuelle est la n°1    
    }
     
    $premiereEntree=($pageActuelle-1)*$messagesParPage; // On calcule la première entrée à lire
     

  if (isset($_POST['parMatiere'])) {
    $_POST['parMatiere'] = htmlspecialchars($_POST['parMatiere']);
    $reponseDevoir = $bdd->query('SELECT * FROM liste_devoirs WHERE date_fin > "'.date("Y-m-d").'" AND matiere = "'.$_POST["parMatiere"].'" ORDER BY date_fin, isResolu, id DESC LIMIT 40');

    $verifReponseVide = $bdd->query('SELECT COUNT(*) AS nbr FROM liste_devoirs WHERE date_fin > "'.date("Y-m-d").'" AND matiere = "'.$_POST["parMatiere"].'" ');
    $verifReponseVide = $verifReponseVide->fetch();

  } elseif (isset($_POST['barreRecherche'])) {
    $_POST['barreRecherche'] = htmlspecialchars($_POST['barreRecherche']);
    $reponseDevoir = $bdd->query('SELECT * FROM liste_devoirs WHERE date_fin > "'.date("Y-m-d").'" AND titre LIKE "%'.$_POST["barreRecherche"].'%" OR date_fin > "'.date("Y-m-d").'" AND sujet LIKE "%'.$_POST["barreRecherche"].'%" ORDER BY date_fin, isResolu, id DESC LIMIT 40');

    $verifReponseVide = $bdd->query('SELECT COUNT(*) AS nbr FROM liste_devoirs WHERE date_fin > "'.date("Y-m-d").'" AND titre LIKE "%'.$_POST["barreRecherche"].'%" OR sujet LIKE "%'.$_POST["barreRecherche"].'%"');
    $verifReponseVide = $verifReponseVide->fetch();

  } else {
    $reponseDevoir = $bdd->query('SELECT * FROM liste_devoirs WHERE date_fin > "'.date("Y-m-d").'" ORDER BY date_fin, isResolu, id DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'');
  }
?>
<body>

    <a href="../../" id="flecheRetourHome"><i class="fas fa-chevron-left"></i></a>
    <?php
    if (isset($verifReponseVide) AND $verifReponseVide['nbr'] > 0) { ?>
      <div id="titrePageBox"><h1>Résultat du tri 🔮</h1></div>
      <?php if (isset($_POST['parMatiere'])) {
        echo '<p>Trié par <b>'.$_POST["parMatiere"].' </b></p>';
      } elseif (isset($_POST['barreRecherche'])) {
        echo '<p>Trié par <q> <b>'.$_POST["barreRecherche"].'</b> </q></p>';
      } 
    } elseif(isset($_POST['parMatiere']) OR isset($_POST['barreRecherche'])) { ?>
      <div id="titrePageBox"><h1>Aucun devoir trouvé 😣</h1></div>
      <?php if (isset($_POST['parMatiere'])) {
        echo '<p>Trié par <b>'.$_POST["parMatiere"].' </b></p>';
      } elseif (isset($_POST['barreRecherche'])) {
        echo '<p>Trié par <q> <b>'.$_POST["barreRecherche"].'</b> </q></p>';
      } ?>
      <p style="font-size: 13px; text-align: center; margin-top: 100px;">Ehooo ! Y'a quelqu'un ?</p>
    <?php } else { ?>
      <div id="titrePageBox"><h1>Tous les devoirs 📦</h1></div>
    <?php } ?>
    

  <?php
     
    while($donneesDevoir=$reponseDevoir->fetch()){

        if ($donneesDevoir['isResolu'] == 1) { $resolu = true; } else { $resolu = false; }

        //on met l'émoji devant la matiere :
        if ($donneesDevoir["matiere"] == "Français") {
          $donneesDevoir["matiere"] = "📚 Français";
        } elseif ($donneesDevoir["matiere"] == "Maths") {
          $donneesDevoir["matiere"] = "📐 Maths";
        } elseif ($donneesDevoir["matiere"] == "Anglais") {
          $donneesDevoir["matiere"] = "💂 Anglais";
        } elseif ($donneesDevoir["matiere"] == "Histoire") {
          $donneesDevoir["matiere"] = "👑 Histoire";
        } elseif ($donneesDevoir["matiere"] == "Geographie") {
          $donneesDevoir["matiere"] = "🗺️ Geographie";
        } elseif ($donneesDevoir["matiere"] == "Espagnol") {
          $donneesDevoir["matiere"] = "💃 Espagnol";
        } elseif ($donneesDevoir["matiere"] == "Allemand") {
          $donneesDevoir["matiere"] = "🥨 Allemand";
        } elseif ($donneesDevoir["matiere"] == "Physique-Chimie") {
          $donneesDevoir["matiere"] = "🔬 Physique-Chimie";
        } elseif ($donneesDevoir["matiere"] == "SVT") {
          $donneesDevoir["matiere"] = "🌋 SVT";
        } elseif ($donneesDevoir["matiere"] == "Informatique") {
          $donneesDevoir["matiere"] = "💻 Informatique";
        } elseif ($donneesDevoir["matiere"] == "Autre") {
          $donneesDevoir["matiere"] = "📁 Autre";
        }
        ?>

          <a class="card">
              <p class="titre"><?php echo $donneesDevoir['titre'] ?></p>
              <div class="matiereClasseBox">
                  <p class="matiere"><?php echo $donneesDevoir['matiere']; ?></p>
                  <p class="classe"><?php echo $donneesDevoir['classe']; ?></p>
                  <?php if ($resolu == true) { echo '<p class="resolu">Résolu</p>'; }?>
              </div>
              <div class="bottom">
                <?php
                $reponseNbrReponses = $bdd->query('SELECT COUNT("id") AS nbrReponses FROM reponses WHERE id_devoir = "'.$donneesDevoir['id'].'"');
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
                <?php //calcul du temps restant
                $dateAujourdhui = date_create(date("Y-m-d"));
                $dateFin = date_create($donneesDevoir['date_fin']);
                $interval = date_diff($dateAujourdhui, $dateFin);
                ?>
                <div class="tempsRestantBox">
                  <i class="far fa-clock"></i>
                  <?php
                  if ($interval->format('%a') == 0) {
                    echo "<p>aujourd'hui</p>";
                  } elseif ($interval->format('%a') == 1) {
                    echo "<p>demain</p>";
                  } else {
                    echo "<p>".$interval->format('%a')." jours</p>";
                  } ?>
                </div>

                <form method="GET" class="cardForm" action="../../pages/page-devoir">
                    <input type="hidden" name="idNumber" value="<?php echo $donneesDevoir['id'] ?>">
                    <button type="submit"><i class="fas fa-arrow-right"></i></button>
                </form>
              </div>
            </a>
      <?php }
    ?> <div id="changementPage"> <?php
    $pagePrecedente = intval($pageActuelle) - 1;
    $pageSuivante = intval($pageActuelle) + 1;

    if (!isset($_POST['parMatiere']) AND !isset($_POST['barreRecherche'])) {
      if ($pagePrecedente >= 1) {
          echo '<a class="btnChangement" href="/devoirs/pages/resultat-tri/index.php?page='.$pagePrecedente.'"><i class="fas fa-arrow-left"></i>Page précédente</a>';
        }
      if ($pageSuivante <= $nombreDePages) {
          echo '<a class="btnChangement" href="/devoirs/pages/resultat-tri/index.php?page='.$pageSuivante.'">Page suivante<i class="fas fa-arrow-right"></i></a>';
      }
    }
    //Fin Pagination
?>
      </div>

</body>

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