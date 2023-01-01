<?php session_start(); 
$afficherPopupBienvenue = false;

if (isset($_POST['popupValide'])) {
  	setcookie("messageBienvenueVu", '1', time() + 365*24*3600);
  	echo '<meta http-equiv="refresh" content="0; URL=" />';
  }

if (!isset($_COOKIE['messageBienvenueVu'])) {
	setcookie("messageBienvenueVu", '0', time() + 365*24*3600);
	$afficherPopupBienvenue = true;
}

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Aide aux devoirs en ligne</title>
  <meta name="viewport" content="width=device-width">
  <link rel="icon" type="image/png" href="/devoirs/images/favicon.png" />
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/30b89b8fe6.js" crossorigin="anonymous"></script>
</head>

<?php
	include("outils/bddConnexion.php"); //connexion à la bdd
	$reponse = $bdd->query('SELECT * FROM liste_devoirs WHERE isResolu = 0 AND date_fin > "'.date("Y-m-d").'" ORDER BY date_fin LIMIT 15');

	//-----------------------
	if (isset($_SESSION['pseudo']) AND isset($_SESSION['id'])) {
		$salut = ', ' . $_SESSION['pseudo'] . ' 👋';
	} else {
		$salut = ' 👋';
	}
?>

<body>
	<div class="top">
    <h1>Salut<?php echo $salut ?></h1>
    <a href="" id="btnAide">?</a>
    <form id="barreRecherche" method="POST" action="pages/resultat-tri/index.php">
    	<input type="text" name="barreRecherche" placeholder="Rechercher un devoir" autocomplete="off" maxlength="90" required>
    	<button type="submit"><i class="fas fa-search"></i></button>
    </form>
    <form method="POST" action="pages/resultat-tri/index.php" id="triParMatiereBox">
    	<button type="submit" name="parMatiere" value="Français" class="matiere">
    		<p class="image">📚</p>
    		<p class="texte">Français</p>
    	</button>
    	<button type="submit" name="parMatiere" value="Maths" class="matiere">
    		<p class="image">📐</p>
    		<p class="texte">Maths</p>
    	</button>
    	<button type="submit" name="parMatiere" value="Anglais" class="matiere">
    		<p class="image">💂</p>
    		<p class="texte">Anglais</p>
    	</button>
    	<button type="submit" name="parMatiere" value="Histoire" class="matiere">
    		<p class="image">👑</p>
    		<p class="texte">Histoire</p>
    	</button>
    	<button type="submit" name="parMatiere" value="Geographie" class="matiere">
    		<p class="image">🗺️</p>
    		<p class="texte">Geographie</p>
    	</button>
    	<button type="submit" name="parMatiere" value="Espagnol" class="matiere">
    		<p class="image">💃</p>
    		<p class="texte">Espagnol</p>
    	</button>
    	<button type="submit" name="parMatiere" value="Allemand" class="matiere">
    		<p class="image">🥨</p>
    		<p class="texte">Allemand</p>
    	</button>
    	<button type="submit" name="parMatiere" value="Physique-Chimie" class="matiere">
    		<p class="image">🔬</p>
    		<p class="texte">Physique-Chimie</p>
    	</button>
    	<button type="submit" name="parMatiere" value="SVT" class="matiere">
    		<p class="image">🌋</p>
    		<p class="texte">SVT</p>
    	</button>
    	<button type="submit" name="parMatiere" value="Informatique" class="matiere">
    		<p class="image">💻</p>
    		<p class="texte">Informatique</p>
    	</button>
    	<button type="submit" name="parMatiere" value="Autre" class="matiere">
    		<p class="image">📁</p>
    		<p class="texte">Autre</p>
    	</button>
    </form>
  </div>
  <div>
  	<section>
  		<div class="titreBox">
  			<h2>Tous les devoirs</h2>
  			<a href="pages/resultat-tri">voir plus <i class="fas fa-chevron-right"></i></a>
  		</div>
  		<div class="cartesContainer">
  			<?php
  			while ($donnees = $reponse->fetch()) { 
  				//on met l'émoji devant la matiere :
  				if ($donnees["matiere"] == "Français") {
  					$donnees["matiere"] = "📚 Français";
  				} elseif ($donnees["matiere"] == "Maths") {
  					$donnees["matiere"] = "📐 Maths";
  				} elseif ($donnees["matiere"] == "Anglais") {
  					$donnees["matiere"] = "💂 Anglais";
  				} elseif ($donnees["matiere"] == "Histoire") {
  					$donnees["matiere"] = "👑 Histoire";
  				} elseif ($donnees["matiere"] == "Geographie") {
  					$donnees["matiere"] = "🗺️ Geographie";
  				}	elseif ($donnees["matiere"] == "Espagnol") {
  					$donnees["matiere"] = "💃 Espagnol";
  				} elseif ($donnees["matiere"] == "Allemand") {
  					$donnees["matiere"] = "🥨 Allemand";
  				} elseif ($donnees["matiere"] == "Physique-Chimie") {
  					$donnees["matiere"] = "🔬 Physique-Chimie";
  				} elseif ($donnees["matiere"] == "SVT") {
  					$donnees["matiere"] = "🌋 SVT";
  				} elseif ($donnees["matiere"] == "Informatique") {
  					$donnees["matiere"] = "💻 Informatique";
  				} elseif ($donnees["matiere"] == "Autre") {
  					$donnees["matiere"] = "📁 Autre";
  				}
  				?>
  				<a class="card">
  					<p class="titre"> <?php echo $donnees["titre"];?> </p>
  					<div class="classeMatiereBox">
  						<p class="matiere"><?php echo $donnees["matiere"]; ?></p>
  						<p class="classe"><?php echo $donnees["classe"]; ?></p>
  					</div>
  					<?php
  						$reponseNbrReponses = $bdd->query('SELECT COUNT("id") AS nbrReponses FROM reponses WHERE id_devoir = "'.$donnees['id'].'"');
  						$donneeNbrReponses = $reponseNbrReponses->fetch();

							if ($donneeNbrReponses['nbrReponses'] == 0) { ?>
  							<div class="nbrReponsesBox nul">
  								<i class="far fa-comments"></i>
  								<p><?php echo $donneeNbrReponses['nbrReponses']; ?> réponse</p>
  							</div>
  						<?php } elseif ($donneeNbrReponses['nbrReponses'] == 1) { ?>
  							<div class="nbrReponsesBox">
  								<i class="far fa-comments"></i>
  								<p><?php echo $donneeNbrReponses['nbrReponses']; ?> réponse</p>
  							</div>
  						<?php } elseif ($donneeNbrReponses['nbrReponses'] > 1) { ?>
  							<div class="nbrReponsesBox">
  								<i class="far fa-comments"></i>
  								<p><?php echo $donneeNbrReponses['nbrReponses']; ?> réponses</p>
  							</div>
  						<?php } 

  					//calcul du temps restant
						$dateAujourdhui = date_create(date("Y-m-d"));
						$dateFin = date_create($donnees['date_fin']);
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
  					<form method="GET" class="cardForm" action="pages/page-devoir">
  						<input type="hidden" name="idNumber" value="<?php echo $donnees['id'] ?>">
  						<button type="submit" value="AIDER">Aider <i class="fas fa-arrow-right"></i></button>
  					</form>
  				</a>
  			<?php }
  			?>
  		</div>
  	</section>
  </div>

  <?php
  if (isset($_COOKIE['messageBienvenueVu']) AND $_COOKIE['messageBienvenueVu'] == false OR $afficherPopupBienvenue == true) {?>
  	<div id="popup">
      <div id="cardPopup">
        <h2>Bienvenue ! 😎</h2>
        <p>Nous te demandons 1 minute d'attention</p>
        <h3>Les coookies 🍪</h3>
        <p>En utilisant notre plateforme, tu acceptes que nous stockions des cookies sur ta machine. Ces cookies sont des données simples que nous stockons puis pouvons récupérer à chaque connexion. Elles permettent le bon fonctionnement de l'application. Par exemple, nous stockons un cookie pour savoir si tu as vu ce message.</p>
        <h3>Besoin d'aide ? 🔥</h3>
        <p>Si tu as besoin d'aide ou qu'il y a un problème à n'importe quel moment, tu peux cliquer sur le bouton "?" en haut à droite de la page d'accueil.</p>
        <form method="POST">
        	<button type="none" name="popupValide">C'est parti !</button>
        </form>
      </div>
    </div>
  <?php }
  ?>

</body>

<nav>
	<div id="block">
		<a href="#" id="home">
			<div class="img" ><img src="images/homePlein.svg"></div>
			<div class="point"></div>
		</a>
		<a href="pages/mes-devoirs" id="mesDevoirs">
			<div class="img" ><i class="far fa-folder"></i></div>
		</a>

		<a href="pages/nouveau-devoir" id="nouveauDevoir">
			<div class="img" >+</div>
		</a>

		<a href="pages/mes-likes" id="likes">
			<div class="img" ><i class="far fa-heart"></i></div>
		</a>
		<a href="pages/compte" id="compte">
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

nav #home {
	display: flex;
	flex-direction: column;
	align-items: center;
}
nav #home img{
	height: 22px;
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