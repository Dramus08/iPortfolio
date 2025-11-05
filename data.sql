-- Table principale des projets
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    category VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    features TEXT,
    technologies TEXT,
    image VARCHAR(255) DEFAULT NULL,
    video_url VARCHAR(255) DEFAULT NULL,             -- Lien vidéo (YouTube/Vimeo ou fichier uploadé)
    customer VARCHAR(150) DEFAULT NULL,
    project_date DATE DEFAULT NULL,
    status ENUM('en cours', 'terminé', 'suspendu') DEFAULT 'en cours',
    link_demo VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TABLE PRINCIPALE DES TAGS
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    color VARCHAR(20) DEFAULT '#0d6efd', -- Pour un code couleur éventuel dans l’UI
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABLE D’ASSOCIATION POLYMORPHE ENTRE TAGS ET AUTRES TABLES
CREATE TABLE IF NOT EXISTS tagged_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tag_id INT NOT NULL,
    tagged_table VARCHAR(100) NOT NULL,   -- Nom de la table (ex: 'projects', 'services', 'testimonials')
    tagged_id INT NOT NULL,               -- ID de l’élément dans cette table
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
    UNIQUE KEY unique_tagged (tag_id, tagged_table, tagged_id)  -- Évite les doublons
);

CREATE TABLE IF NOT EXISTS app_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,              -- success, error, info, warning
    message TEXT NOT NULL,
    context VARCHAR(255) DEFAULT NULL,      -- ex: "ArticleController::store"
    user_id INT DEFAULT NULL,               -- facultatif (si connecté)
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


/*
-- TABLE DES SERVICES
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    icon VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TABLE DES CLIENTS
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    logo VARCHAR(255),
    website VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TABLE DES PROJETS
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    image VARCHAR(255),
    video VARCHAR(255), -- Aperçu vidéo du projet (URL ou chemin local)
    features JSON, -- Exemple : ["Suivi des dépenses", "Objectifs d'épargne"]
    technologies JSON, -- Exemple : ["React Native", "Node.js"]
    service_id INT, -- Lien vers un service principal
    customer_id INT, -- Lien vers le client associé
    start_date DATE,
    end_date DATE,
    status ENUM('draft', 'in_progress', 'completed', 'archived') DEFAULT 'draft',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
);

-- TABLE DES PORTFOLIOS (ensemble de projets ou réalisations)
CREATE TABLE IF NOT EXISTS portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    cover_image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TABLE DE LIAISON PORTFOLIO <-> PROJECTS (many-to-many)
CREATE TABLE IF NOT EXISTS portfolio_projects (
    portfolio_id INT NOT NULL,
    project_id INT NOT NULL,
    PRIMARY KEY (portfolio_id, project_id),
    FOREIGN KEY (portfolio_id) REFERENCES portfolio(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

-- TABLE DES TÉMOIGNAGES (avis clients)
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    project_id INT, -- Témoignage lié à un projet précis
    name VARCHAR(150),
    role VARCHAR(150),
    message TEXT,
    rating TINYINT CHECK (rating BETWEEN 1 AND 5),
    photo VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);

-- TABLE DES TAGS GLOBAUX (communs à plusieurs entités)
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL
);

-- TABLE POLYMORPHIQUE POUR GÉRER LES TAGS ASSOCIÉS À PLUSIEURS TABLES
CREATE TABLE IF NOT EXISTS tagged_items (
    tag_id INT NOT NULL,
    tagged_table VARCHAR(100) NOT NULL, -- Exemple : 'projects', 'services', 'portfolio', 'testimonials'
    tagged_id INT NOT NULL,
    PRIMARY KEY (tag_id, tagged_table, tagged_id),
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);
*/
