<?php

echo '<h1>'.$user->username.'</h1>';
echo '<h1> <b>Status : '.$user->role.'</b></h1>';

echo "<div style='padding:20px;border:0.8px solid #222;border-radius:8px;' >Veuiller verifier votre boite de messagerie et suivre le lien recu !<b>  </br>".$user->email.'</b>';
$reset_superdmin_click=\Router\Router::route('reset_default_password',['slug'=>$user->slug]);
if ($this->isAdmin() ) echo "<form  class='pt-4' action='".$reset_superdmin_click."' method='POST'><button class='btn btn-primary'>reset password</button></form>";
echo '</div></br>';

echo "<div class='text-center'><a class='btn btn-secondary w-25' href='".\Router\Router::route('index')."'>Page d'accueil</a>";

echo "<h3 style='text-align:center;font-weight:600;padding:20px 5px;background:#222;color:#fff;margin:25px 100px; border-radius:12px;border:none; '>".'Merci de Votre Fidelite'.'</h3></div>';