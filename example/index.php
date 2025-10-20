<?php

/**
 * Fichier d'exemple pour la bibliothèque beriyack/api-client.
 *
 * Ce script démontre comment utiliser la classe ApiClient pour effectuer
 * des requêtes GET, POST, PUT, et DELETE sur une API RESTful.
 *
 * Pour l'exécuter :
 * 1. Assurez-vous d'avoir lancé `composer install` à la racine du projet.
 * 2. Lancez un serveur PHP local, par exemple : `php -S localhost:8000 -t example`
 * 3. Ouvrez http://localhost:8000 dans votre navigateur.
 */

// Affiche les erreurs pour un débogage facile
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définit l'en-tête pour un affichage HTML propre
header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';

use Beriyack\Client\ApiClient;

// On exécute la logique PHP en premier et on stocke les résultats dans des variables.
$post = null;
$createdPost = null;
$updatedPost = null;
$deleteResponse = null;
$errorMessage = null;
$newPostData = [
    'title'  => 'Mon Super Titre',
    'body'   => 'Ceci est le contenu de mon nouveau post.',
    'userId' => 1,
];
$updatedPostData = [
    'id'     => 1,
    'title'  => 'Titre mis à jour',
    'body'   => 'Le contenu a été modifié avec succès.',
    'userId' => 1,
];

try {
    // Chemin vers votre fichier de certificat local
    $caCertPath = __DIR__ . '/WE1.crt';
    if (!file_exists($caCertPath)) {
        throw new Exception("Le fichier de certificat '{$caCertPath}' est introuvable. Assurez-vous qu'il est bien dans le dossier 'example'.");
    }

    $client = new ApiClient('https://jsonplaceholder.typicode.com', [], [
        CURLOPT_CAINFO => $caCertPath
    ]);

    $post = $client->get('/posts/1');
    $createdPost = $client->post('/posts', $newPostData);
    $updatedPost = $client->put('/posts/1', $updatedPostData);
    $deleteResponse = $client->delete('/posts/1');
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemple d'utilisation de ApiClient</title>
    <style>body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 2em; line-height: 1.6; } h2 { color: #0056b3; border-bottom: 2px solid #eee; padding-bottom: 5px; } pre { background-color: #f4f4f4; padding: 1em; border: 1px solid #ddd; border-radius: 5px; white-space: pre-wrap; word-break: break-all; } .success { color: green; } .error { color: red; }</style>
</head>
<body>
    <h1>Démonstration de la bibliothèque <code>beriyack/api-client</code></h1>

    <?php if ($errorMessage): ?>
        <h2><span class="error">Une erreur est survenue !</span></h2>
        <pre class="error"><?= htmlspecialchars($errorMessage) ?></pre>
    <?php else: ?>
        <h2>1. Initialisation du client</h2>
        <p class="success">Client initialisé avec succès pour l'URL de base : https://jsonplaceholder.typicode.com</p>

        <h2>2. Récupérer une ressource (GET)</h2>
        <p>Appel de <code>$client->get('/posts/1')</code>...</p>
        <h3>Résultat :</h3>
        <pre><?= htmlspecialchars(print_r($post, true)) ?></pre>

        <h2>3. Créer une nouvelle ressource (POST)</h2>
        <p>Appel de <code>$client->post('/posts', ...)</code> avec les données suivantes :</p>
        <pre><?= htmlspecialchars(print_r($newPostData, true)) ?></pre>
        <h3>Réponse de l'API (création simulée) :</h3>
        <pre><?= htmlspecialchars(print_r($createdPost, true)) ?></pre>

        <h2>4. Mettre à jour une ressource (PUT)</h2>
        <p>Appel de <code>$client->put('/posts/1', ...)</code> avec les données suivantes :</p>
        <pre><?= htmlspecialchars(print_r($updatedPostData, true)) ?></pre>
        <h3>Réponse de l'API (mise à jour simulée) :</h3>
        <pre><?= htmlspecialchars(print_r($updatedPost, true)) ?></pre>

        <h2>5. Supprimer une ressource (DELETE)</h2>
        <p>Appel de <code>$client->delete('/posts/1')</code>...</p>
        <h3>Réponse de l'API (suppression simulée) :</h3>
        <pre><?php var_dump($deleteResponse); ?></pre>
    <?php endif; ?>

</body>
</html>
