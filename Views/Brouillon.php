<?php
require "../vendor/autoload.php";

use Utils\Mailer;


$mailer=new Mailer();

// Email simple
//$mailer->setFrom()
  //     ->addAddress('inouaismail@gmail.com', 'inoua ismail')
    //   ->setHTMLContent('Sujet', '<h1>Contenu HTML</h1>')
      // ->send();

// Email de confirmation


// Configuration personnalisée
/*
$mailer->sendConfirmationEmail(
    'inouaismail@gmail.com', 
    'John Doe', 
    'https://www.commentcamarche.net/applis-sites/mail/441-se-connecter-a-gmail/'
);

echo "Message envoye avec success !";
        $config = [
            'host' => 'smtp.mondomaine.com',
            'username' => 'contact@mondomaine.com',
            'password' => 'mon-mot-de-passe'
        ];

//$mailer = new Mailer($config);
*/
// Email avec pièces jointes
$mailer->setFrom('inouaismailcoding@gmail.com', 'II coding App')
       ->addAddress('inouaismail@gmail.com', 'Inoua Ismail')
       ->addCC('iicoding0602@gmail.com')
       //->addAttachment('C:\xampp\htdocs\portfolio\public\projects\images\1761007409_XNXX_8inch_black_cock_baise_sissy_360p.mp4')
       ->setHTMLContent('Facture', '<p>Veuillez trouver votre facture ci-jointe.</p>')
       ->send();

echo "successfful";