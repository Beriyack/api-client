<?php

namespace Beriyack;

use Exception;

/**
 * Class ApiClient
 * 
 * Une bibliothèque PHP simple et efficace pour interagir avec des API RESTful, 
 * conçue pour simplifier vos requêtes HTTP (GET, POST, PUT, DELETE).
 * 
 * @package Beriyack\ApiClient
 * @author Beriyack
 * @version 1.0.0
 */
class ApiClient
{
    /**
     * Exécute une requête GET.
     *
     * @param string $url L'URL de l'API.
     * @param array $options Tableau d'options cURL supplémentaires (facultatif).
     * @return mixed Les données de la réponse de l'API, décodées si JSON.
     * @throws Exception Si la requête échoue.
     */
    public static function get(string $url, array $options = [])
    {
        return self::request('GET', $url, [], $options);
    }

    /**
     * Exécute une requête POST.
     *
     * @param string $url L'URL de l'API.
     * @param array $data Les données à envoyer dans le corps de la requête.
     * @param array $options Tableau d'options cURL supplémentaires (facultatif).
     * @return mixed Les données de la réponse de l'API, décodées si JSON.
     * @throws Exception Si la requête échoue.
     */
    public static function post(string $url, array $data = [], array $options = [])
    {
        return self::request('POST', $url, $data, $options);
    }

    /**
     * Exécute une requête PUT.
     *
     * @param string $url L'URL de l'API.
     * @param array $data Les données à envoyer dans le corps de la requête.
     * @param array $options Tableau d'options cURL supplémentaires (facultatif).
     * @return mixed Les données de la réponse de l'API, décodées si JSON.
     * @throws Exception Si la requête échoue.
     */
    public static function put(string $url, array $data = [], array $options = [])
    {
        return self::request('PUT', $url, $data, $options);
    }

    /**
     * Exécute une requête DELETE.
     *
     * @param string $url L'URL de l'API.
     * @param array $options Tableau d'options cURL supplémentaires (facultatif).
     * @return mixed Les données de la réponse de l'API, décodées si JSON.
     * @throws Exception Si la requête échoue.
     */
    public static function delete(string $url, array $options = [])
    {
        return self::request('DELETE', $url, [], $options);
    }

    /**
     * Méthode générique pour exécuter toutes les requêtes HTTP.
     *
     * @param string $method La méthode HTTP (GET, POST, PUT, DELETE).
     * @param string $url L'URL de l'API.
     * @param array $data Les données à envoyer dans le corps de la requête (pour POST/PUT).
     * @param array $options Tableau d'options cURL supplémentaires.
     * @return mixed Les données de la réponse de l'API, décodées si JSON.
     * @throws Exception Si la requête cURL échoue.
     */
    private static function request(string $method, string $url, array $data = [], array $options = [])
    {
        $ch = curl_init();

        // Options cURL de base
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retourne le transfert comme une chaîne de caractères au lieu de l'afficher directement.

        // Configuration de la méthode HTTP
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Encodage des données en JSON par défaut
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Encodage des données en JSON par défaut
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'GET':
            default:
                // Pas d'action spécifique nécessaire pour GET, les données sont généralement dans l'URL (query parameters)
                break;
        }

        // Ajout des options cURL personnalisées
        foreach ($options as $opt => $value) {
            curl_setopt($ch, $opt, $value);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            throw new Exception("Erreur cURL lors de la requête : " . $error_msg);
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Décoder la réponse JSON si c'est le cas
        $decodedResponse = json_decode($response, true);

        // Gérer les erreurs HTTP (par exemple, 4xx, 5xx)
        if ($http_code >= 400) {
            $error_message = "Erreur HTTP $http_code : " . ($decodedResponse['message'] ?? $response);
            throw new Exception($error_message);
        }

        return $decodedResponse ?: $response; // Retourne le JSON décodé ou la réponse brute si ce n'est pas du JSON
    }
}