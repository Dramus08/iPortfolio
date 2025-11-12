<!DOCTYPE html>
<html lang="fr">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title;?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lightbox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
      <link rel="stylesheet" href="<?=ASSETS."css/toast.css";?>">
</head>
<body>
    <!-- Navigation -->
    <?php include INCLUDES.'nav.php' ;?>
        <?php
    echo "<p style='margin:30px;'>  ".$user->username."</p>";

        echo $_SESSION['user']['slug'];
?>
                    <pre><?php //print_r($Router::getRoutes()); ?></pre>

    <?php 
   // echo isset($_SESSION['user']) && isset($_SESSION['email_confirmed']) && ($_SESSION['email_confirmed'] === 1 ) ?'cest okay':'email non valide';
    
    
    //include INCLUDES.'footer.php' ;?>

   <?php include INCLUDES.'vendor-js-file.php' ;?> 
    <!-- Custom JS -->
    <script src="assets/js/home.js"></script>
</body>
</html>



























