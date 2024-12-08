<?php

class Autoload{
  public static function register() {
    // Enregistre la méthode d'autoload
    // Appelera la méthode 'autoload' à chaque fois qu'une class inconnue sera instanciée
    // en lui envoyant la class demandée (__CLASS__) en paramètre
    spl_autoload_register([__CLASS__, 'autoload']);
  }

 
public static function autoload($class): void 
{
  //echo $class;
    // List of directories to search
    $directories = [
        __DIR__ .'/../controllers/',
        __DIR__ .'/../models/',
        __DIR__ .'/../utils/',
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    die('<p>Fichier introuvable</p>');
}}