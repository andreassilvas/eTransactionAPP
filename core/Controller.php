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
        // Transforme le tableau associatif en variables
        extract($data);

        // Inclut le fichier de vue
        require_once "../app/views/$view.php";
    }
}
