<?php

namespace Beriyack\Client;

use Exception;

/**
 * A simple and effective object-oriented PHP library for interacting with RESTful APIs.
 *
 * @package Beriyack\Client
 * @author Beriyack
 * @version 3.1.0
 */
class ApiClient
{
    private string $baseUri;
    private array $defaultHeaders;
    private array $defaultCurlOptions;

    /**
     * @param string $baseUri The base URI for the API (e.g., 'https://api.example.com/v1').
     * @param array $defaultHeaders Default headers to send with every request (e.g., for authorization).
     * @param array $defaultCurlOptions Default cURL options for every request.
     */
    public function __construct(string $baseUri = '', array $defaultHeaders = [], array $defaultCurlOptions = [])
    {
        $this->baseUri = rtrim($baseUri, '/');
        $this->defaultHeaders = $defaultHeaders;
        $this->defaultCurlOptions = $defaultCurlOptions;
    }

    /**
     * Sends a GET request.
     *
     * @param string $endpoint The API endpoint (e.g., '/users').
     * @param array $queryParams Query parameters to append to the URL.
     * @param array $headers Additional headers for this specific request.
     * @param array $curlOptions Additional cURL options for this specific request.
     * @return mixed The decoded JSON response.
     * @throws Exception If the request fails.
     */
    public function get(string $endpoint, array $queryParams = [], array $curlOptions = [], array $headers = []): mixed
    {
        $url = $this->buildUrl($endpoint, $queryParams);
        return $this->request('GET', $url, null, $headers, $curlOptions);
    }

    /**
     * Sends a POST request.
     *
     * @param string $endpoint The API endpoint.
     * @param mixed|null $data The data to send in the request body.
     * @param array $headers Additional headers.
     * @param array $curlOptions Additional cURL options.
     * @return mixed The decoded JSON response.
     * @throws Exception If the request fails.
     */
    public function post(string $endpoint, mixed $data = null, array $curlOptions = [], array $headers = []): mixed
    {
        $url = $this->buildUrl($endpoint);
        return $this->request('POST', $url, $data, $headers, $curlOptions);
    }

    /**
     * Sends a PUT request.
     *
     * @param string $endpoint The API endpoint.
     * @param mixed|null $data The data to send in the request body.
     * @param array $headers Additional headers.
     * @param array $curlOptions Additional cURL options.
     * @return mixed The decoded JSON response.
     * @throws Exception If the request fails.
     */
    public function put(string $endpoint, mixed $data = null, array $curlOptions = [], array $headers = []): mixed
    {
        $url = $this->buildUrl($endpoint);
        return $this->request('PUT', $url, $data, $headers, $curlOptions);
    }

    /**
     * Sends a DELETE request.
     *
     * @param string $endpoint The API endpoint.
     * @param array $headers Additional headers.
     * @param array $curlOptions Additional cURL options.
     * @return mixed The decoded JSON response.
     * @throws Exception If the request fails.
     */
    public function delete(string $endpoint, array $curlOptions = [], array $headers = []): mixed
    {
        $url = $this->buildUrl($endpoint);
        return $this->request('DELETE', $url, null, $headers, $curlOptions);
    }

    private function buildUrl(string $endpoint, array $queryParams = []): string
    {
        $url = $this->baseUri . '/' . ltrim($endpoint, '/');
        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }
        return $url;
    }

    private function request(string $method, string $url, mixed $data = null, array $headers = [], array $curlOptions = []): mixed
    {
        $ch = curl_init();

        $finalHeaders = array_merge($this->defaultHeaders, $headers);
        $httpHeaders = [];
        foreach ($finalHeaders as $key => $value) {
            $httpHeaders[] = "$key: $value";
        }

        // Merge cURL options. User-provided options ($curlOptions) should override the defaults.
        // The `+` operator gives precedence to the left array, so we put the user options first.
        // A more explicit alternative would be: array_replace($this->defaultCurlOptions, $curlOptions);
        $options = $curlOptions + $this->defaultCurlOptions;
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_CUSTOMREQUEST] = $method;
        $options[CURLOPT_HTTPHEADER] = $httpHeaders;

        if ($data !== null) {
            // Détecter le Content-Type pour encoder les données correctement
            $contentType = 'application/json'; // Par défaut
            foreach ($finalHeaders as $key => $value) {
                if (strtolower($key) === 'content-type') {
                    $contentType = $value;
                    break;
                }
            }

            if (strpos($contentType, 'application/x-www-form-urlencoded') !== false) {
                $options[CURLOPT_POSTFIELDS] = http_build_query($data);
            } else {
                // Comportement par défaut : JSON
                $options[CURLOPT_POSTFIELDS] = json_encode($data);
                // S'assurer que l'en-tête JSON est présent s'il n'a pas été défini manuellement
                $contentTypeHeaderFound = false;
                foreach ($finalHeaders as $key => $value) {
                    if (strtolower($key) === 'content-type') {
                        $contentTypeHeaderFound = true;
                        break;
                    }
                }
                if (!$contentTypeHeaderFound) {
                    $options[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
                }
            }
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }

        if ($httpCode >= 400) {
            throw new Exception("HTTP Error {$httpCode}: " . $response, $httpCode);
        }

        return json_decode($response, true);
    }
}
