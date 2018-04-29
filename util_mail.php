<?php

  /*****************************************************/
  /** Fonction envoyant un mail au destinataire
  /** indiqué dans le fichier de configuration
  /** $emailEmetteur : email de la personne qui envoie  le mail
  /** $sujet : sujet message
  /** $message : message à envoyer
  /*****************************************************/
  function envoi_mail ($emailEmetteur, $sujet, $message) { 
    
    // Analyse des paramètres du fichier de configuration
    $ini_array = parse_ini_file("config.ini", TRUE);
        
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "From: emilie@hair-style.fr\n";
    $headers .='Reply-To: '.$emailEmetteur."\r\n";
    $headers .='Content-Type: text/html; charset="utf-8"'."\r\n";
    $headers .='Content-Transfer-Encoding: 8bit';

    $testSujet = str_replace(' ','',$sujet);
    if(strlen($testSujet) == 0) {
        $sujet = 'Message provenant de www.hair-style.fr';
    }
    
    // Récupération du destinataire dans le fichier de config
    $destinataire = $ini_array["mail"]["destinataire"];

    // On peut enfin l'envoyer
    return mail($destinataire, $sujet, $message, $headers);
  
  }
  
  
  /*****************************************************/
  /** Fonction envoyant un mail aux destinataires
  /** indiqué dans le fichier de configuration
  /** $emailEmetteur : email de la personne qui envoie  le mail
  /** $sujet : sujet message
  /** $message : message à envoyer
  /*****************************************************/
  function envoi_mail2 ($emailEmetteur, $sujet, $message) { 
    
    // Analyse des paramètres du fichier de configuration
    $ini_array = parse_ini_file("config.ini", TRUE);
        
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "From: emilie@hair-style.fr\n";
    $headers .='Reply-To: '.$emailEmetteur."\r\n";
    $headers .='Content-Type: text/html; charset="utf-8"'."\r\n";
    $headers .='Content-Transfer-Encoding: 8bit';

    $testSujet = str_replace(' ','',$sujet);
    if(strlen($testSujet) == 0) {
        $sujet = 'Message provenant de www.hair-style.fr';
    }
    $return  = TRUE;
    
    // Pour chaque destinataire du mail
    foreach($ini_array["mail"] as $destinataire) 
    {  
      // On peut enfin l'envoyer
      $return = $return && mail($destinataire, $sujet, $message, $headers);
    }
    
    return $return;
  }
?>