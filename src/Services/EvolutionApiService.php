<?php

namespace Brunojsbr\EvolutionApi\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\GuzzleException;

class EvolutionApiService
{
    private Client $client;
    private string $baseUrl;
    private string $apiKey;
    private string $instanceName;

    public function __construct(string $baseUrl, string $apiKey, string $instanceName)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->instanceName = $instanceName;

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'apikey' => $this->apiKey
            ]
        ]);
    }

    /**
     * Cria uma nova instância do WhatsApp
     */
    public function createInstance(string $name): array
    {
        return $this->post('/instance/create', [
            'instanceName' => $name,
        ]);
    }

    /**
     * Obtém o QR Code de uma instância
     */
    public function getInstanceQR(string $instance): array
    {
        return $this->get("/instance/qr/$instance");
    }

    /**
     * Verifica o status de uma instância
     */
    public function getInstanceStatus(string $instance): array
    {
        return $this->get("/instance/status/$instance");
    }

    /**
     * Desconecta uma instância
     */
    public function disconnectInstance(string $instance): array
    {
        return $this->delete("/instance/logout/$instance");
    }

    /**
     * Envia uma mensagem de texto
     */
    public function sendText(string $instance, string $to, string $message): array
    {
        return $this->post("/message/text/$instance", [
            'number' => $to,
            'message' => $message
        ]);
    }

    /**
     * Envia uma imagem
     */
    public function sendImage(string $instance, string $to, string $url, ?string $caption = null): array
    {
        return $this->post("/message/image/$instance", [
            'number' => $to,
            'imageUrl' => $url,
            'caption' => $caption
        ]);
    }

    /**
     * Envia um documento
     */
    public function sendDocument(string $instance, string $to, string $url, string $filename): array
    {
        return $this->post("/message/document/$instance", [
            'number' => $to,
            'documentUrl' => $url,
            'fileName' => $filename
        ]);
    }

    /**
     * Envia um áudio
     */
    public function sendAudio(string $instance, string $to, string $url): array
    {
        return $this->post("/message/audio/$instance", [
            'number' => $to,
            'audioUrl' => $url
        ]);
    }

    /**
     * Envia um vídeo
     */
    public function sendVideo(string $instance, string $to, string $url, ?string $caption = null): array
    {
        return $this->post("/message/video/$instance", [
            'number' => $to,
            'videoUrl' => $url,
            'caption' => $caption
        ]);
    }

    /**
     * Envia uma localização
     */
    public function sendLocation(string $instance, string $to, float $latitude, float $longitude, ?string $name = null): array
    {
        return $this->post("/message/location/$instance", [
            'number' => $to,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'name' => $name
        ]);
    }

    /**
     * Envia um contato
     */
    public function sendContact(string $instance, string $to, string $number, string $name): array
    {
        return $this->post("/message/contact/$instance", [
            'number' => $to,
            'contact' => [
                'number' => $number,
                'name' => $name
            ]
        ]);
    }

    /**
     * Envia botões
     */
    public function sendButton(string $instance, string $to, string $text, array $buttons): array
    {
        return $this->post("/message/button/$instance", [
            'number' => $to,
            'message' => $text,
            'buttons' => $buttons
        ]);
    }

    /**
     * Envia uma lista
     */
    public function sendList(string $instance, string $to, string $text, array $sections): array
    {
        return $this->post("/message/list/$instance", [
            'number' => $to,
            'message' => $text,
            'sections' => $sections
        ]);
    }

    /**
     * Faz uma requisição GET
     */
    private function get(string $endpoint): array
    {
        try {
            $response = $this->client->get($endpoint);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Evolution API Error: ' . $e->getMessage(), [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Faz uma requisição POST
     */
    private function post(string $endpoint, array $data): array
    {
        try {
            $response = $this->client->post($endpoint, ['json' => $data]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Evolution API Error: ' . $e->getMessage(), [
                'endpoint' => $endpoint,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Faz uma requisição DELETE
     */
    private function delete(string $endpoint): array
    {
        try {
            $response = $this->client->delete($endpoint);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Evolution API Error: ' . $e->getMessage(), [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
