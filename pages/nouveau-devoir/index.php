<?php session_start(); ?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Nouveau devoir</title>
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

	$compte = array('nom' => 'Demougeot', 'prenom' => 'Nils');
?>

<body>
  <h1>Ajouter un devoir</h1>
  <h2>Informations sur le devoir</h2>
  <form method="POST">
  	<input type="text" name="titre" placeholder="Titre du devoir" class="titre" autofocus required autocomplete="off" maxlength="150" minlength="10">
  	<textarea name="sujet" placeholder="Sujet du devoir" class="sujet" required autocomplete="off" maxlength="2000" minlength="20"></textarea>
    <div id="selectorsBox">
      <div>
        <h2>Matière</h2>
      	<select name="matiere" class="matiere" required>
      		<option value="Français">📚 Français</option>
      		<option value="Maths">📐 Maths</option>
      		<option value="Anglais">💂 Anglais</option>
      		<option value="Histoire">👑 Histoire</option>
      		<option value="Geographie">🗺️ Geographie</option>
      		<option value="Espagnol">💃 Espagnol</option>
      		<option value="Allemand">🥨 Allemand</option>
          <option value="Physique-Chimie">🔬 Physique-Chimie</option>
          <option value="SVT">🌋 SVT</option>
          <option value="Informatique">💻 Informatique</option>
          <option value="Autre">📁 Autre</option>
        </select>
      </div>
      <div id="separation"></div>
      <div>
        <h2>Classe</h2>
      	<select name="classe" class="classe" required>
      		<option value="Seconde">Seconde</option>
      		<option value="Première">Première</option>
      		<option value="Terminale">Terminale</option>
      	</select>
      </div>
    </div>
    <h2>À rendre pour le :</h2>
    <?php 
    echo '<input type="date" name="dateFin" required value="'.date("Y-m-d").'" min="'.date("Y-m-d").'" max="'.date('Y-m-d', strtotime('+20 days')).'"; >'; 
    ?>
    <p id="publiePar">publié par <b><?php echo $_SESSION['pseudo'] ?></b></p>
    <div id="checkboxBox">
      <input type="checkbox" name="resterAnonyme" id="checkbox">
      <label for="checkbox">Je préfère rester anonyme</label>
    </div>
  	<input type="submit" name="envoyer" class="envoyer">
  </form>

  <?php 

  	$requete = false;

  	if (isset($_POST['envoyer'])) {
      $_POST['titre'] = htmlspecialchars($_POST['titre']);
      $_POST['sujet'] = htmlspecialchars($_POST['sujet']);
      $_POST['matiere'] = htmlspecialchars($_POST['matiere']);
      $_POST['classe'] = htmlspecialchars($_POST['classe']);
      $_POST['dateFin'] = htmlspecialchars($_POST['dateFin']);
      $_POST['resterAnonyme'] = htmlspecialchars($_POST['resterAnonyme']);
      if (isset($_POST['resterAnonyme'])) {
        $requete = $bdd->query("INSERT INTO liste_devoirs (titre, sujet, matiere, classe, date_fin, id_createur, createur_anonyme) VALUES ('".addslashes($_POST['titre'])."', '".addslashes($_POST['sujet'])."', '".$_POST['matiere']."', '".$_POST['classe']."', '".$_POST['dateFin']."', '".$_SESSION['id']."', 1)");
      } else {
  		  $requete = $bdd->query("INSERT INTO liste_devoirs (titre, sujet, matiere, classe, date_fin, id_createur) VALUES ('".addslashes($_POST['titre'])."', '".addslashes($_POST['sujet'])."', '".$_POST['matiere']."', '".$_POST['classe']."', '".$_POST['dateFin']."', '".$_SESSION['id']."')");
  	}
  }

     
    // Teste si le devoir contient un mot interdit
    if (isset($_POST['envoyer'])) {
      $reponseBlackList = $bdd->query('SELECT * FROM black_list');
      $contientBlackList = false;

      while ($donneesBlackList = $reponseBlackList->fetch()) {
        if(strpos($_POST['titre'], $donneesBlackList['mot']) !== false OR strpos($_POST['sujet'], $donneesBlackList['mot']) !== false){
          $contientBlackList = true;
        }
    }
    }

    if (isset($contientBlackList) AND isset($_POST['envoyer'])) {
      if ($contientBlackList == false AND $requete) { ?>
        <div id="popup">
          <div id="card">
            <h2>Importation réussie 😀</h2>
            <p><b>Astuce:</b> Rendez-vous dans la partie <q>Mes Devoirs</q> (<i class="far fa-folder"></i>) du menu pour suivre ce devoir</p>
            <form method="POST" action="../../">
                <button type="submit">OK</button>
            </form>
          </div>
        </div>
      <?php } elseif ($contientBlackList == true AND $requete) { 
        $requeteSupprimer = $bdd->query('DELETE FROM liste_devoirs WHERE titre = "'.$_POST['titre'].'" AND sujet = "'.$_POST['sujet'].'"')
        ?>
        <div id="popup">
          <div id="card">
            <h2>Importation annulée 🤬</h2>
            <p style="color: red;">Votre message contient un mot interdit !</p>
            <form method="POST">
                <button type="submit">OK</button>
            </form>
          </div>
        </div>
      <?php } else { ?>
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
    }
    ?>

</body>

<nav>
  <div id="block">
    <a href="../../" id="home">
      <div class="img" ><img src="../../images/homeBordure.svg" style="height: 22px; margin-top: 5px;"></div>
    </a>
    <a href="../../pages/mes-devoirs" id="mesDevoirs">
      <div class="img" ><i class="far fa-folder"></i></div>
    </a>

    <a href="../../" id="nouveauDevoir">
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
  background: rgb(255,77,77);
  background: linear-gradient(145deg, rgba(255,77,77,1) 0%, rgba(167,0,0,1) 100%);
  height: 65px;
  width: 65px;
  border-radius: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  box-shadow: 0px 3px 20px rgba(255,0,0,0.4);
  font-size: 55px;
  transform: rotate(45deg);
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

<?php } ?>

</html>