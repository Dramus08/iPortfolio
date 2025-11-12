-- Table pour stocker les tokens de réinitialisation
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT '0',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `token` (`token`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Procédure pour demander une réinitialisation
DELIMITER //
CREATE PROCEDURE RequestPasswordReset(IN user_email VARCHAR(220))
BEGIN
    DECLARE user_id INT;
    DECLARE reset_token VARCHAR(255);
    DECLARE token_expiry DATETIME;
    
    -- Vérifier si l'email existe
    SELECT id INTO user_id FROM users WHERE email = user_email AND is_active = 1;
    
    IF user_id IS NOT NULL THEN
        -- Générer un token unique
        SET reset_token = SHA2(CONCAT(NOW(), RAND(), user_id), 256);
        SET token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR);
        
        -- Insérer ou mettre à jour le token
        INSERT INTO password_resets (user_id, token, expires_at, used)
        VALUES (user_id, reset_token, token_expiry, 0)
        ON DUPLICATE KEY UPDATE 
            token = VALUES(token),
            expires_at = VALUES(expires_at),
            used = 0,
            created_at = CURRENT_TIMESTAMP;
        
        SELECT 1 as success, reset_token as token, user_id;
    ELSE
        SELECT 0 as success, 'Email non trouvé' as message;
    END IF;
END //
DELIMITER ;

-- Procédure pour vérifier la validité du token
DELIMITER //
CREATE PROCEDURE VerifyResetToken(IN reset_token VARCHAR(255))
BEGIN
    DECLARE user_id INT;
    DECLARE expiry DATETIME;
    DECLARE is_used TINYINT;
    
    SELECT pr.user_id, pr.expires_at, pr.used 
    INTO user_id, expiry, is_used
    FROM password_resets pr
    WHERE pr.token = reset_token;
    
    IF user_id IS NOT NULL THEN
        IF is_used = 1 THEN
            SELECT 0 as valid, 'Token déjà utilisé' as message;
        ELSEIF expiry < NOW() THEN
            SELECT 0 as valid, 'Token expiré' as message;
        ELSE
            SELECT 1 as valid, 'Token valide' as message, user_id;
        END IF;
    ELSE
        SELECT 0 as valid, 'Token invalide' as message;
    END IF;
END //
DELIMITER ;

-- Procédure pour réinitialiser le mot de passe
DELIMITER //
CREATE PROCEDURE ResetPassword(
    IN reset_token VARCHAR(255),
    IN new_password TEXT
)
BEGIN
    DECLARE user_id INT;
    DECLARE token_valid INT DEFAULT 0;
    
    -- Vérifier la validité du token
    CALL VerifyResetToken(reset_token);
    
    -- Récupérer le user_id si le token est valide
    SELECT pr.user_id INTO user_id 
    FROM password_resets pr 
    WHERE pr.token = reset_token 
    AND pr.used = 0 
    AND pr.expires_at > NOW();
    
    IF user_id IS NOT NULL THEN
        -- Mettre à jour le mot de passe
        UPDATE users 
        SET password = new_password,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = user_id;
        
        -- Marquer le token comme utilisé
        UPDATE password_resets 
        SET used = 1 
        WHERE token = reset_token;
        
        SELECT 1 as success, 'Mot de passe réinitialisé avec succès' as message;
    ELSE
        SELECT 0 as success, 'Token invalide ou expiré' as message;
    END IF;
END //
DELIMITER ;