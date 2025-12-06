<?php
return [
    'host' => $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com',
    'username' => $_ENV['SMTP_USERNAME'] ?? 'your-email@gmail.com',
    'password' => $_ENV['SMTP_PASSWORD'] ?? 'your-app-password',
    'name' => $_ENV['SMTP_FROM_NAME'] ?? 'Your App Name',
    'smtp_auth' => true,
    'smtp_secure' => PHPMailer::ENCRYPTION_STARTTLS,
    'port' => 587,
    'charset' => 'UTF-8',
    'debug' => $_ENV['APP_DEBUG'] ?? false
];