<?php

/**
 * Définit un message flash dans la session.
 *
 * Les messages flash sont stockés dans $_SESSION['flash'] et
 * sont supprimés après avoir été lus une fois.
 *
 * @param string $key Clé identifiant le message
 * @param mixed $value Valeur du message
 */
function setFlash(string $key, $value): void
{
    $_SESSION['flash'][$key] = $value;
}

/**
 * Récupère et supprime un message flash de la session.
 *
 * Si le message existe, il est retourné et supprimé immédiatement.
 * Sinon, retourne null.
 *
 * @param string $key Clé du message à récupérer
 * @return mixed|null Valeur du message ou null si inexistant
 */
function getFlash(string $key)
{
    if (isset($_SESSION['flash'][$key])) {
        $value = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]); // Supprimer le message après lecture
        return $value;
    }

    return null;
}
