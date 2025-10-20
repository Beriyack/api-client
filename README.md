# 🚀 API Client

Une bibliothèque PHP simple et efficace pour interagir avec des API RESTful. Elle fournit un client HTTP orienté objet et configurable pour simplifier et sécuriser vos requêtes `GET`, `POST`, `PUT`, et `DELETE` en utilisant cURL.

---

## ✨ Points Forts

*   **Approche Orientée Objet :** Instanciez des clients pour différentes API, chacune avec sa propre configuration.
*   **Configuration Flexible :** Définissez une URL de base, des en-têtes par défaut (ex: `Authorization`) et des options cURL pour chaque client.
*   **Opérations CRUD Complètes :** Supporte les méthodes HTTP `GET`, `POST`, `PUT`, et `DELETE`.
*   **Gestion Automatique :** Gère l'encodage des données JSON et les en-têtes `Content-Type`.
*   **Gestion Robuste des Erreurs :** Lance des exceptions claires en cas d'erreur cURL ou de code de statut HTTP invalide.

---

## 🛠️ Prérequis et Installation

*   PHP 8.0 ou supérieur
*   L'extension PHP `cURL`

1.  **Exigence :** Assurez-vous d'avoir [Composer](https://getcomposer.org/) installé sur votre système.
2.  **Ajoutez la dépendance** à votre projet via Composer :

    ```bash
    composer require beriyack/api-client
    ```

    Cela installera la librairie dans votre dossier `vendor/` et mettra à jour l'autoloader de Composer.

3.  **Utilisez l'autoloader de Composer** dans votre projet :

    ```php
    <?php
    require_once 'vendor/autoload.php';

    use Beriyack\Client\ApiClient;
    ?>
    ```

---

## 📖 Utilisation

### 1. Client simple (GET)

Créez une instance du client en spécifiant l'URL de base de l'API que vous souhaitez interroger.

```php
use Beriyack\Client\ApiClient;

try {
    $client = new ApiClient('https://jsonplaceholder.typicode.com');
    
    // Récupérer des données (GET)
    $post = $client->get('/posts/1');
    print_r($post);

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
```

### 2. Créer et Mettre à jour des ressources (POST / PUT)

Les méthodes `post()` et `put()` permettent d'envoyer des données, qui seront automatiquement encodées en JSON.

```php
use Beriyack\Client\ApiClient;

try {
    $client = new ApiClient('https://jsonplaceholder.typicode.com');

    // Créer une ressource (POST)
    $newPostData = [
        'title' => 'Mon Nouveau Titre',
        'body' => 'Contenu de mon nouveau post.',
        'userId' => 1
    ];
    $createdPost = $client->post('/posts', $newPostData);
    echo "Post créé :\n";
    print_r($createdPost);

    // Mettre à jour une ressource (PUT)
    $updatedPostData = ['title' => 'Titre Mis à Jour'];
    $updatedPost = $client->put('/posts/1', $updatedPostData);
    echo "\nPost mis à jour :\n";
    print_r($updatedPost);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
```

### 3. Supprimer une ressource (DELETE)

```php
use Beriyack\Client\ApiClient;

try {
    $client = new ApiClient('https://jsonplaceholder.typicode.com');
    $response = $client->delete('/posts/1');
    
    // Une réponse vide ou un objet vide indique généralement un succès
    echo "Ressource supprimée avec succès.";
    print_r($response);

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
```

### 4. Client avec authentification et options cURL

Le constructeur vous permet de pré-configurer le client avec des en-têtes (par exemple, pour une clé d'API) et des options cURL qui seront utilisées pour chaque requête.

```php
use Beriyack\Client\ApiClient;

try {
    $headers = [
        'Authorization' => 'Bearer VOTRE_TOKEN_SECRET',
        'Accept'        => 'application/json',
    ];

    // Exemple pour un environnement de dev local qui nécessite un certificat spécifique
    $curlOptions = [
        CURLOPT_CAINFO => __DIR__ . '/path/to/your/cacert.pem',
        CURLOPT_TIMEOUT => 15, // Timeout de 15 secondes pour chaque requête
    ];

    $client = new ApiClient('https://api.exemple.com/v2', $headers, $curlOptions);

    // Chaque requête utilisera automatiquement le token et les options cURL
    $userData = $client->get('/user');
    print_r($userData);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
```

---

## 🤝 Contribution

Les contributions sont les bienvenues \! Si vous avez des idées d'améliorations, de nouvelles fonctionnalités ou des corrections de bugs, n'hésitez pas à ouvrir une *issue* ou à soumettre une *pull request*.

---

## 📄 Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](https://www.google.com/search?q=LICENSE) pour plus de détails.

-----

## 📧 Contact

Pour toute question ou suggestion, vous pouvez me contacter via [Beriyack](https://github.com/Beriyack).

-----
