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
        <a class="navbar-brand " href="/#page-top">Hair Style</a>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link " href="/#mobilite">Mobilité</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="/#tarifs">Tarifs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="/#portfolio">Portfolio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#livredor">Livre d'or</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="/#contact">Contact</a>
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

echo '<p class="text-light">hote='.$ini_array["bdd"]["hote"].'</p>';
echo '<p class="text-light">utilisateur='.$ini_array["bdd"]["utilisateur"].'</p>';
echo '<p class="text-light">motdepasse='.$ini_array["bdd"]["motdepasse"].'</p>';
echo '<p class="text-light">basededonnee='.$ini_array["bdd"]["basededonnee"].'</p>';

    // Connexion à la base de donnée
    $conn=mysqli_connect($ini_array["bdd"]["hote"], $ini_array["bdd"]["utilisateur"], $ini_array["bdd"]["motdepasse"], $ini_array["bdd"]["basededonnee"]) or die("Impossible de se connecter : ".mysql_error());   

    var_dump($_POST);

echo '<p class="text-light">name='.$_POST['name'].'</p>';
echo '<p class="text-light">email='.$_POST['email'].'</p>';
echo '<p class="text-light">message='.$_POST['message'].'</p>';

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
            envoi_mail2(htmlspecialchars($_POST['email']), "Message dans Livre d'or", stripslashes($corpsMail));

          }
        }
    }



?>
        </div>
        <hr class="my-4">
        <p class="mb-5 text-light">Vous pouvez ajouter un commentaire dans le livre d'or avec le formulaire suivant : </p>
        <form class="form-horizontal" role="form" id="addCommentBlock" action="/livredor.php" method="post">
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
