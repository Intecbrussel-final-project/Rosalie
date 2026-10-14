<?php
//Maison_Rosalie/config-dev.php
const DB_HOST = "localhost";
const DB_LOGIN = "root";
const DB_PWD = "";
const DB_NAME = "maison_rosalie";
const DB_PORT = 3306;
const DB_CHARSET = "utf8mb4";

const DB_TYPE = "mysql";

// racine de notre site pour PHP
const RACINE_PATH = __DIR__;

// Mailer (Symfony Mailer, smtp.webdev-cf2m.be)
const MAILER_EMAIL = "";
const MAILER_PASSWORD = "";

// adresse qui reçoit les notifications des messages de contact (vide = MAILER_EMAIL)
const ADMIN_EMAIL = "";

// adresse du site utilisée dans les liens des e-mails
const SITE_URL = "http://localhost/";
