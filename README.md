# 🚀 API Client

Une bibliothèque PHP orientée objet pour interagir avec des API RESTful. Elle fournit un client HTTP configurable pour simplifier et sécuriser vos requêtes `GET`, `POST`, `PUT`, et `DELETE`.

---

## ✨ Fonctionnalités

*   **Approche Orientée Objet :** Instanciez des clients pour différentes API, chacune avec sa propre configuration.
*   **Configuration Flexible :** Définissez une URL de base, des en-têtes par défaut (ex: `Authorization`) et des options cURL pour chaque client.
*   **Opérations CRUD Complètes :** Supporte les méthodes HTTP `GET`, `POST`, `PUT`, et `DELETE`.
*   **Gestion Automatique :** Gère l'encodage des données JSON et les en-têtes `Content-Type`.
*   **Gestion Robuste des Erreurs :** Lance des exceptions claires en cas d'erreur cURL ou de code de statut HTTP invalide.

---

## 🛠️ Installation

Cette bibliothèque est conçue pour être facilement installable via Composer. 
Elle nécessite les extensions PHP standards comme `cURL`.

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

### Client simple

Créez une instance du client en spécifiant l'URL de base de l'API que vous souhaitez interroger.

```php
use Beriyack\Client\ApiClient;

try {
    $client = new ApiClient('https://jsonplaceholder.typicode.com');
    
    // Récupérer des données (GET)
    $data = $client->get('/posts/1');
    print_r($data);

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
```

### Créer une ressource (POST)

```php
use Beriyack\ApiClient;

try {
    $newPost = [
        'title' => 'Mon Nouveau Titre',
        'body' => 'Contenu de mon nouveau post.',
        'userId' => 1
    ];
    $response = ApiClient::post('https://jsonplaceholder.typicode.com/posts', $newPost);
    print_r($response);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
```

### Mettre à jour une ressource (PUT)

```php
use Beriyack\ApiClient;

try {
    $updatedPost = [
        'id' => 1,
        'title' => 'Titre Mis à Jour',
        'body' => 'Nouveau contenu pour le post.',
        'userId' => 1
    ];
    $response = ApiClient::put('https://jsonplaceholder.typicode.com/posts/1', $updatedPost);
    print_r($response);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
```

### Supprimer une ressource (DELETE)

```php
use Beriyack\ApiClient;

try {
    $response = ApiClient::delete('https://jsonplaceholder.typicode.com/posts/1');
    print_r($response); // Souvent vide ou un objet vide pour une suppression réussie
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
```

### Options cURL avancées

Vous pouvez passer un tableau d'options cURL supplémentaires à toutes les méthodes.

```php
use Beriyack\ApiClient;

try {
    $options = [
        CURLOPT_TIMEOUT => 10, // Timeout de 10 secondes
        CURLOPT_SSL_VERIFYPEER => false // ATTENTION: À utiliser avec précaution et jamais en production sans bonne raison !
    ];
    $data = ApiClient::get('https://some-api.com/data', $options);
    print_r($data);
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
