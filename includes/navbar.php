 <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?=$Router::route('index');?>">
                <span>IIC</span>oding
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$Router::route('index');?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?=$Router::route('about');?>">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$Router::route('service_list');?>">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$Router::route('project_list');?>">Projets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$Router::route('portfolio_list');?>">Portfolio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$Router::route('pricing');?>">Tarifs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$Router::route('contact');?>">Contact</a>
                    </li>
                </ul>
                <a href="<?=$Router::route('contact');?>" class="btn btn-primary ms-3">Me contacter</a>
                <a href="<?=$Router::route('dashboard_admin');?>" class="btn btn-secondary ms-1">Dashboard</a>
                <?php if($this->isAuthenticated()): ?>
                    <a href="<?=$Router::route('logout');?>" class="btn btn-warning ms-1">Deconnecte</a>
                <?php else : ?>
                    <a href="<?=$Router::route('login');?>" class="btn btn-success ms-1">se connecter</a>
                <?php endif ; ?>
            </div>
        </div>
    </nav>