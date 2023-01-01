<?php session_start(); ?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Aide aux devoirs en ligne</title>
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="style.css">
  <link rel="icon" type="image/png" href="/devoirs/images/favicon.png" />
  <script src="script.js"></script>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/30b89b8fe6.js" crossorigin="anonymous"></script>
</head>

<?php
    if(isset($_SESSION['id']) AND isset($_GET['idNumber'])){
        $autorise = true;
    } else {
        $autorise = false;
        echo '<meta http-equiv="refresh" content="0; URL=/devoirs/pages/compte/form-connexion/" />';
    }

if ($autorise == true) {


	include("../../outils/bddConnexion.php"); //connexion à la bdd
	//on ajoute une vue au devoir
	$requeteAjoutVue = $bdd->query("UPDATE liste_devoirs SET nbr_vues = nbr_vues + 1 WHERE id = '".$_GET['idNumber']."'");

	$reponseDevoir = $bdd->query("SELECT * FROM liste_devoirs WHERE id = '".$_GET['idNumber']."'");
	$reponseCommentaires = $bdd->query("SELECT * FROM reponses WHERE id_devoir = '".$_GET['idNumber']."' ORDER BY date_creation ASC "); 

	$donneesDevoir = $reponseDevoir->fetch();

	$reponseCompteDevoir = $bdd->query("SELECT pseudo FROM utilisateurs WHERE id = '".$donneesDevoir['id_createur']."'");
	$donneesCompteDevoir = $reponseCompteDevoir->fetch();

	$reponseLike = $bdd->query("SELECT * FROM likes WHERE id_devoir = '".$_GET['idNumber']."' AND id_utilisateur = '".$_SESSION['id']."'");
	$donneesLike = $reponseLike->fetch();


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
	}	elseif ($donneesDevoir["matiere"] == "Espagnol") {
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
<body>
	<div id="top">
		<a href="../../"><i class="fas fa-chevron-left"></i></a>
		<div id="nbrVues">
			<i class="far fa-eye"></i>
			<p><b><?php echo $donneesDevoir['nbr_vues']; ?></b> vues</p>
		</div>

		<h1><?php echo $donneesDevoir['titre']; ?></h1>
	  	<h3>par <b><?php if($donneesDevoir['createur_anonyme'] == 1){ echo "anonyme"; } else { echo $donneesCompteDevoir['pseudo']; } ?></b></h3>
	  	<h2><?php echo $donneesDevoir['matiere'] . " | " . $donneesDevoir['classe']; ?></h2>
	  	
	  	<form id="btnBox" method="POST">
	  	<?php
	  	if (isset($_POST['aime']) OR $donneesLike) { ?>
	  		<button type="submit" value="aime" name="aime" id="liked"><i class="fas fa-heart"></i> Aimé</button>
		<?php } else{ ?>
			<button type="submit" value="aime" name="aime"><i class="far fa-heart"></i> Aimer</button>
		<?php } ?>
	  		
	  		<div class="separation"></div>
	  		<button type="button" name="report" onclick="reportPopup()"><i class="far fa-flag"></i> Signaler</button>
	  		<div class="separation"></div>

	  		<button type="button" id="partagerBtn" onclick="partagerPopup()"><i class="far fa-share-square"></i> Partager</button>

	  	</form>

	  	<!-- Popups des boutons du form -->
	  	<div id="popupReport">
	  		<div class="popupCard">
	  			<button type="button" class="annulerBtn" onclick="document.location.reload()"><i class="fas fa-times"></i> Annuler <i class="fas fa-times"></i></button>
		  		<p><i class="fas fa-exclamation-triangle"></i> Souhaitez vous vraiment signaler ce devoir ?</p>
		  		<form method="POST">
		  			<input type="submit" name="report" value="Signaler">
		  		</form>
		  	</div>
	  	</div>

	  	<div id="popupPartager">
	  		<div class="popupCard">
	  			<button type="button" class="annulerBtn" onclick="document.location.reload()"><i class="fas fa-times"></i> Annuler <i class="fas fa-times"></i></button>
		  		<p id="copierLeLien">Copier le lien</p>
		  		<div id="copyBox">
		  			<input type="text" id="copyUrl" value=<?php echo "http://localhost/devoirs/pages/page-devoir/?idNumber=".$_GET['idNumber']; ?>>
		  			<button type="button" id="copyBtn"><i class="far fa-clone"></i></button>
		  		</div>
		  	</div>
	  	</div>

	  	<script type="text/javascript">
	  		function reportPopup() {
	  			document.getElementById('popupReport').style.display = "flex";
 			}

 			function partagerPopup() {
	  			document.getElementById('popupPartager').style.display = "flex";
 			}

 			function copy() {
			  var copyText = document.querySelector("#copyUrl");
			  copyText.select();
			  document.execCommand("copy");
			  document.getElementById("copierLeLien").innerHTML = "le lien a été copié !";
			  document.getElementById("copierLeLien").style.color = "#1BB025";
			}

			document.querySelector("#copyBtn").addEventListener("click", copy);
	  	</script>

	  	<?php
	  		if (isset($_POST['aime'])) {
	  			if ($donneesLike) {
	  				$requete = $bdd->query("DELETE FROM likes WHERE id_devoir = '".$_GET['idNumber']."' AND id_utilisateur = '".$_SESSION['id']."'");
	  				echo '<meta http-equiv="refresh" content="0; URL=/devoirs/pages/page-devoir/?idNumber='.$_GET['idNumber'].'" />';
	  			} else {
	  				$requete = $bdd->query("INSERT INTO likes (id_utilisateur, id_devoir) VALUES ('".$_SESSION['id']."', '".$_GET['idNumber']."')");
	  				echo '<meta http-equiv="refresh" content="0; URL=/devoirs/pages/page-devoir/?idNumber='.$_GET['idNumber'].'" />';
	  			}
	  		}

	  		if (isset($_POST['report'])) {
				$requete = $bdd->query("UPDATE liste_devoirs SET isReported = 1 WHERE id = '".$_GET['idNumber']."'");
	  		}
	  	?>


	</div>

	 <h2 class="titreIntermediaire">Sujet du devoir<div class="line"></div></h2>
	 <p id="sujet" style="white-space: pre-wrap;"><?php echo $donneesDevoir['sujet'] ?></p>

	 <h2 class="titreIntermediaire">Réponses<div class="line"></div></h2>

  <?php
  	$reponseNbrReponses = $bdd->query('SELECT COUNT(*) AS nbr FROM reponses WHERE id_devoir = "'.$_GET['idNumber'].'"');
  	$nbrReponses = $reponseNbrReponses->fetch();
  	if ($nbrReponses['nbr'] < 1) { ?>
  		<p style="color: grey; font-style: italic; text-align: center;">Ehoo! Y'a quelqu'un ici ?</p>
  	<?php }


  	while ($donneesCommentaires = $reponseCommentaires->fetch()) {
  		$reponseCompteCommentaire = $bdd->query("SELECT pseudo FROM utilisateurs WHERE id = '".$donneesCommentaires['id_createur']."'");
		$donneesCompteCommentaire = $reponseCompteCommentaire->fetch();

		$reponseIdAdmins = $bdd->query("SELECT id_compte FROM admins");
	    while ($donneesIdAdmins = $reponseIdAdmins->fetch()) {
	        if ($donneesCommentaires['id_createur'] == $donneesIdAdmins['id_compte']) {
	            $isModo = true;
	        } else {
	            $isModo = false;
	        }
	      }

		if ($donneesCommentaires['isSolution'] == 1) { ?>
			<div class="reponseBox reponseBoxSolution">
				<?php
				if ($isModo == true) { ?>
					<div class="pseudoBox">
						<h3><?php echo $donneesCompteCommentaire['pseudo'] ?></h3>
						<div class="modoLabel">
							<p><i class="fas fa-user-shield"></i> Modérateur</p>
						</div>
					</div>
				<?php } else { ?>
  					<h3><?php echo $donneesCompteCommentaire['pseudo'] ?></h3>
  				<?php } ?>
  				<p style="white-space: pre-wrap;"><?php echo $donneesCommentaires['message']; ?></p>
  				<div class="dateCreation">
  					<?php echo substr($donneesCommentaires['date_creation'], 8, 2)."/".substr($donneesCommentaires['date_creation'], 5, 2)."/".substr($donneesCommentaires['date_creation'], 0, 4)." à ".substr($donneesCommentaires['date_creation'], 11, 2)."h".substr($donneesCommentaires['date_creation'], 14, 2); ?>
  				</div>
		<?php } else { ?>
			<div class="reponseBox">
				<?php if ($isModo == true) { ?>
					<div class="pseudoBox">
						<h3><?php echo $donneesCompteCommentaire['pseudo'] ?></h3>
						<div class="modoLabel">
							<p><i class="fas fa-user-shield"></i> Modérateur</p>
						</div>
					</div>
				<?php } else { ?>
  					<h3><?php echo $donneesCompteCommentaire['pseudo'] ?></h3>
  				<?php } ?>
  				<p style="white-space: pre-wrap;"><?php echo $donneesCommentaires['message']; ?></p>
  				<div class="dateCreation">
  					<?php echo substr($donneesCommentaires['date_creation'], 8, 2)."/".substr($donneesCommentaires['date_creation'], 5, 2)."/".substr($donneesCommentaires['date_creation'], 0, 4)." à ".substr($donneesCommentaires['date_creation'], 11, 2)."h".substr($donneesCommentaires['date_creation'], 14, 2); ?>
  				</div>

  				<?php
  				if (isset($_POST['signalerMessage'])) {
  					$requete = $bdd->query("UPDATE reponses SET isReported = 1 WHERE id = '".$_POST['idReponse']."'");
  				}
  				
  				if ($donneesDevoir['id_createur'] != $_SESSION['id']){ ?>
	  				<form method="POST" class="signalerMessage">
	  					<input type="hidden" name="idReponse" value=<?php echo $donneesCommentaires['id']; ?>>
	  					<button type="submit" name="signalerMessage"><i class="far fa-flag"></i></button>
	  				</form>

  				
			<?php }
			}
  	
  			if ($donneesDevoir['id_createur'] == $_SESSION['id'] AND $donneesDevoir['isResolu'] != 1) { ?>
  				<form class="checkBox" method="POST">
  					<input type="hidden" name="idReponse" value="<?php echo $donneesCommentaires['id'] ?>">
  					<button type="submit" name="isSolution" value=""><i class="fas fa-check"></i></button>
  				</form>
  				<?php if(isset($_POST['isSolution'])){
  					$requete = $bdd->query("UPDATE reponses SET isSolution = 1 WHERE id = '".$_POST['idReponse']."'");
  					$requete = $bdd->query("UPDATE liste_devoirs SET isResolu = 1 WHERE id = '".$_GET['idNumber']."'");
  					echo '<meta http-equiv="refresh" content="0; URL=/devoirs/pages/page-devoir/?idNumber='.$_GET['idNumber'].'" />';
  				}
  			}

			if ($donneesCommentaires['isSolution'] == 1) { ?>
				<div class="solutionSigne">
  					<div><i class="fas fa-check"></i></div>
  				</div>
			<?php } ?>
  	</div>
<?php } ?>

<?php
  	if (isset($_POST['envoyer'])) {
  		$_POST['message'] = htmlspecialchars($_POST['message']);
  		$requete = $bdd->query("INSERT INTO reponses (id_devoir, message, id_createur) VALUES ('".$donneesDevoir['id']."', '".addslashes($_POST['message'])."', '". $_SESSION['id'] ."')");
  		echo '<meta http-equiv="refresh" content="0; URL=/devoirs/pages/page-devoir/?idNumber='.$_GET['idNumber'].'" />';
  	}
?>
	<form id="repondre" method="POST">
		<h3>Aider sur ce devoir</h3>
  		<textarea name="message" placeholder="Ecrivez votre réponse" required autocomplete="off" maxlength="800"></textarea>
  		<input type="submit" name="envoyer">
  	</form>



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

<?php } ?>
</html>