<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="fr" name="language"/>
    <meta lang="fr" content="Hair Style, Hair Style Coiffure à domicile, Coiffure a domicile, livre d'or, Coiffeuse, Coiffeur, Mariage, Gironde, Bordeaux, Aquitaine, Emilie" name="keywords"/>
    <meta lang="fr" content="Coiffure à domicile" name="description"/>
    <meta content="Hair Style - Coiffure à domicile" name="author"/>
    <meta content="Hair Style - Coiffure à domicile" name="creator"/>
    <meta content="Hair Style - Coiffure à domicile" name="DC.title"/>
    <meta content="Hair Style - Coiffure à domicile" name="DC.creator"/>
    <meta content="Copyright Hair Style. All rights reserved." name="DC.rights"/>


    <title>Hair Style - Livre d'or</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

    <!-- Plugin CSS -->
    <link href="vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/creative.css" rel="stylesheet">

  </head>

  <body class="bg-dark" id="page-top">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-default  text-primary fixed-top" id="livredorNav">
      <div class="container">
        <a class="navbar-brand " href="./index.php#page-top">Hair Style</a>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link " href="./index.php#mobilite">Mobilité</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="./index.php#tarifs">Tarifs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="./index.php#portfolio">Portfolio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#livredor">Livre d'or</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="./index.php#contact">Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <section id="livredor">
      <div class="container" >
        <div class="row no-gutters text-light">
          <div class="col-lg-12 col-sm-12">
            <h2 class="text-uppercase text-center text-light">Livre d'or</h2>
            <hr class="my-4">
          </div>
        </div>
        <div id="messagesList" class="text-light">
<?php
    // Analyse des paramètres de la base de donnée
    $ini_array = parse_ini_file("config.ini", TRUE);

    // Connexion à la base de donnée
    $conn=mysqli_connect($ini_array["bdd"]["hote"], $ini_array["bdd"]["utilisateur"], $ini_array["bdd"]["motdepasse"], $ini_array["bdd"]["basededonnee"]) or die("Impossible de se connecter : ".mysql_error());   

    if (isset($_POST['name']) AND isset($_POST['email']) AND isset($_POST['message'])) {
        $nom = mysqli_real_escape_string($conn, htmlspecialchars($_POST['name']));
        $email = mysqli_real_escape_string($conn, htmlspecialchars($_POST['email'])); 
        $message = mysqli_real_escape_string($conn, htmlspecialchars($_POST['message'])); 

        if ((trim($nom) != '') && (trim($message) != '') && (substr_count($message, 'http') == 0)) {
          // On peut enregistrer
          $result = mysqli_query($conn, "insert into hrs_livre_d_or (LDO_NOM, LDO_MAIL, LDO_MESSAGE) values('$nom', '$email', '$message')");
          if (!$result) {
              die('Requête invalide : ' . mysql_error());
          } else {

            // --------------- Etape -----------------
            // Envoi d'un mail 
            // ---------------------------------------
            $corpsMail = '<html><head><title>Titre</title></head><body>';
            $corpsMail .= 'Nom : '.htmlspecialchars($_POST['name']).'<br/>';
            $corpsMail .= 'Adresse email : '.htmlspecialchars($_POST['email']).'<br/>';

            // Conversion des retours chariot
            $order   = array("\r\n", "\n", "\r");
            $replace = '<br />';
            $newstr = str_replace($order, $replace, htmlspecialchars($_POST['message']));
            $corpsMail .= '<br/>';
            $corpsMail .= $newstr;

            $corpsMail .= '</body></html>';

            require_once("util_mail.php");

            // Envoi du mail
            $result2 = envoi_mail2(htmlspecialchars($_POST['email']), "Message dans Livre d'or", stripslashes($corpsMail));
            if (!$result2) {
              die('Envoi mail KO');
            }

          }
        }
    }

    // --------------- Etape 2 -----------------
    // On écrit les liens vers chacune des pages
    // -----------------------------------------
    // On met dans une variable le nombre de messages qu'on veut par page
    $nombreDeMessagesParPage = 3;
    // On récupère le nombre total de messages
    $result = mysqli_query($conn, 'select count(*) as NB_MESSAGES from hrs_livre_d_or');
    $donnees = mysqli_fetch_assoc($result);
    $totalDesMessages = $donnees['NB_MESSAGES'];
    // On calcule le nombre de pages à créer
    $nombreDePages  = ceil($totalDesMessages / $nombreDeMessagesParPage);

    if (isset($_GET['index'])) {
      $page = $_GET['index'];
    }
    else {
      $page = 1; // On se met sur la page 1 (par défaut)
    }

    // On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
    $premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

    // Récupération des messages dans la base de donénes
    $reponse = mysqli_query($conn, 'select * from hrs_livre_d_or order by ldo_date desc limit ' . $premierMessageAafficher . ', ' . $nombreDeMessagesParPage);

    // Nombre de messages récupérés
    $nb_messages = mysqli_num_rows($reponse);

    // Parcours des messages lus en base
    while ($donnees = mysqli_fetch_assoc($reponse)) {
        echo '<div class="alert alert-primary">
                  <h4 class="alert-heading">'.stripslashes($donnees['ldo_nom']).'</h4>
                  <p class="message_livredor">'.stripslashes(nl2br($donnees['ldo_message'])) . '</p>
              </div>';
        }


    // On affiche les pages si il y en a plusieurs 
    if($nombreDePages > 1) {
      /*echo '<p class="page_livredor">Page : ';
      for ($i = 1 ; $i <= $nombreDePages ; $i++) {
        if ($page == $i) {
          echo '<a href="livredor.php?index=' . $i . '"><strong>' . $i . '</strong>  </a>';
        } else {
          echo '<a href="livredor.php?index=' . $i . '">' . $i . '  </a> ';
        }
      }
      echo '</p>';
*/
      echo '<nav class="navbar  bg-dark navbar-dark">
              <ul class="pagination">';
      for ($i = 1 ; $i <= $nombreDePages ; $i++) {
        if ($page == $i) {
          echo '<li class="page-item active"><a class="page-link" href="./livredor.php?index=' . $i . '">'. $i .'</a></li>';
        } else {
          echo '<li class="page-item"><a class="page-link" href="./livredor.php?index=' . $i . '">'. $i .'</a></li>';
        }
      }
      echo '
          </ul>
        </nav>';
    }

    // On n'oublie pas de fermer la connexion à MySQL
    mysqli_close($conn);

?>
        </div>
        <hr class="my-4">
        <p class="mb-5 text-light">Vous pouvez ajouter un commentaire dans le livre d'or avec le formulaire suivant : </p>
        <form class="form-horizontal" role="form" id="addCommentBlock" action="./livredor.php" method="post">
          <div class="form-group ">
            <label class="control-label text-light" for="name">Nom:</label>
            <input class="form-control" id="name" name="name">
          <div class="form-group ">
          </div>
            <label class="control-label text-light col-sm-offset-2" for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email">
          </div>
          <div class="form-group">
            <label class="control-label text-light" for="pwd">Message:</label>
            <textarea class="form-control" rows="5" id="message" name="message"></textarea>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-primary btn-xl js-scroll-trigger">Envoyer</button>
          </div>
        </form> 
      </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="vendor/scrollreveal/scrollreveal.min.js"></script>
    <script src="vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/creative.min.js"></script>

  </body>

</html>
