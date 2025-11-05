
    <!-- Favicons -->
    <link href="<?= ASSETS.'img'.DIRECTORY_SEPARATOR.'favicon.png';?>" rel="icon">
    <link href="<?= ASSETS.'img'.DIRECTORY_SEPARATOR.'apple-touch-icon.png';?>" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= SCRIPTS.'fontawesome'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'all.min.css' ;?>">

     <!-- Vendor CSS Files -->
     <link href="<?= ASSETS.'vendor'.DIRECTORY_SEPARATOR.'bootstrap-icons'.DIRECTORY_SEPARATOR.'bootstrap-icons.css';?> " rel="stylesheet">
    <link href="<?= ASSETS.'vendor'.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'bootstrap.min.css';?> " rel="stylesheet">
   
    <link href="<?= ASSETS.'vendor'.DIRECTORY_SEPARATOR.'aos'.DIRECTORY_SEPARATOR.'aos.css';?> " rel="stylesheet">
    <link href="<?= ASSETS.'vendor'.DIRECTORY_SEPARATOR.'glightbox'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'glightbox.min.css';?> " rel="stylesheet">
    <link href="<?= ASSETS.'vendor'.DIRECTORY_SEPARATOR.'swiper'.DIRECTORY_SEPARATOR.'swiper-bundle.min.css';?> " rel="stylesheet">

  <!-- Main CSS File -->
  <link href="<?= ASSETS .'css'.DIRECTORY_SEPARATOR.'main.css';?> " rel="stylesheet">



      <!-- =======================================================
  * Template Name: BizPage
  * Template URL: https://bootstrapmade.com/bizpage-bootstrap-business-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
    
    <title>Document</title>








<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="<?='/'.HTDOCS.'/'; ?>" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 class="sitename">BizPage</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="<?='/'.HTDOCS.'/'; ?>" class="active">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#portfolio">Portfolio</a></li>
          <li><a href="#team">Team</a></li>
          <li><a href="blog.html">Blog</a></li>
          <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="#about">About</a></li>
          <li class="nav-item">
          <a class="nav-link" href="<?='/'.HTDOCS."/brouillon" ;?>">Brouillon</a>
        </li> 
        <li class="nav-item">
          <a class="nav-link" href="<?='/'.HTDOCS."/mailer" ;?>">les derniers Mails</a>
        </li> 
        <?php if(isset($_SESSION['auth']) && $_SESSION['role']=='admin' ): ?>
        <li class="nav-item">
          <a class="nav-link" href="<?='/'.HTDOCS.'/admin/'; ?>">Administration des utilisateurs</a>
        </li> 
        <?php endif  ?>
      </ul>
      <ul class="navbar-nav ml-auto">
        <?php if(isset($_SESSION['auth'])){
          ?>
             <li class="nav-item">
          <a class="nav-link btn btn-outline-success" href="<?='/'.HTDOCS.'/logout/'; ?>">Deconnecter</a>
        </li> 

          <?php
        }
        else{
          ?>
              <li class="nav-item">
          <a class="nav-link btn btn-outline-success" href="<?='/'.HTDOCS.'/login/'; ?>">Se Connecter</a>
        </li> 
        <?php
        } ?>
         
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
</header>