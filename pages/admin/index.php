<?php session_start(); ?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Admin</title>
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
    $autorise = false;
    include("../../outils/bddConnexion.php"); //connexion à la bdd 

    $reponseIdAdmins = $bdd->query("SELECT id_compte FROM admins");
    while ($donneesIdAdmins = $reponseIdAdmins->fetch()) {
        if ($_SESSION['id'] == $donneesIdAdmins['id_compte']) {
            $autorise = true;
        } else {
            $autorise = false;
            echo '<meta http-equiv="refresh" content="0; URL=/devoirs/" />';
        }
    }


if ($autorise == true) {

?>

<body>

    <h1>Espace Administrateur</h1>

    <form method="POST">
        <h2>Supprimer un devoir</h2>
        <input type="number" name="supprimerDevoir" placeholder="ID" required>
        <input type="submit" name="supprimerDevoirSubmit" value="Supprimer">
    </form><br>
    <form method="POST">
        <h2>Marquer devoir comme résolu</h2>
        <input type="number" name="devoirResolu" placeholder="ID" required>
        <input type="submit" name="devoirResoluSubmit" value="Marquer comme résolu">
    </form><br>
    <?php
        if (isset($_POST['supprimerDevoir'])) {
            $requete = $bdd->query('DELETE FROM liste_devoirs WHERE id = "'.$_POST['supprimerDevoir'].'"');
        }
        if (isset($_POST['devoirResolu'])) {
            $requete = $bdd->query('UPDATE liste_devoirs SET isResolu = 1 WHERE id = "'.$_POST['devoirResolu'].'"');
        }
    ?>

    <form>
        <h2>Liste des devoirs rapportés</h2>
        <?php

        $reponseDevoirs = $bdd->query('SELECT * FROM liste_devoirs WHERE isReported = 1');
        while ($donneesDevoirs = $reponseDevoirs->fetch()) { ?>
            <div class="devoirBox">
                <p><b><?php echo $donneesDevoirs['titre']; ?></b></p>
                <p><?php echo $donneesDevoirs['sujet']; ?></p>
                <div class="btnsBox">
                    <form method="GET" action="../../pages/page-devoir">
                        <input type="hidden" name="idNumber" value="<?php echo $donneesDevoirs['id'] ?>">
                        <button type="submit">Voir <i class="fas fa-arrow-right"></i></button>
                    </form>

                    <form method="POST">
                        <input type="hidden" name="supprimerDevoirReported" value="<?php echo $donneesDevoirs['id'] ?>">
                        <button type="submit" style="color: red;">Supprimer <i class="fas fa-trash-alt"></i></button>
                    </form>
                    <?php
                    if (isset($_POST['supprimerDevoirReported'])) {
                        $requete = $bdd->query('DELETE FROM liste_devoirs WHERE id = "'.$_POST['supprimerDevoirReported'].'"');
                    } ?>

                    <form method="POST">
                        <input type="hidden" name="devoirVerifie" value="<?php echo $donneesDevoirs['id'] ?>">
                        <button type="submit" style="color: green;">Vérifié <i class="fas fa-user-check"></i></button>
                    </form>
                    <?php
                    if (isset($_POST['devoirVerifie'])) {
                        $requete = $bdd->query('UPDATE liste_devoirs SET isReported = 0 WHERE id = "'.$_POST['devoirVerifie'].'"');
                    } ?>
                </div>
            </div><br>
        <?php }
        ?>
    </form>

</body>

<nav>
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
</nav>
<style type="text/css">
  nav{
  background-color: white;
  position: fixed;
  bottom: 0px;
  left: 0px;
  width: 100%;
  height: 50px;
  z-index: 1000;
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

nav #mesDevoirs{
  margin-right: 40px;
}
nav #likes{
  margin-left: 40px;
}
</style>

<?php } ?>

</html>