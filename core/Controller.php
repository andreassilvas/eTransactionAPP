<?php
namespace Core;
class Controller
{
    /**
     * Charge une vue et lui passe des données
     *
     * @param string $view  Nom du fichier de la vue (sans extension)
     * @param array  $data  Tableau associatif de données à extraire pour la vue
     */
    public function view($view, $data = [])
    {
        extract($data);
        $file = __DIR__ . '/../app/Views/' . $view . '.php';
        if (!file_exists($file)) {
            die("View file not found: $file");
        }
        require_once $file;
    }
}
