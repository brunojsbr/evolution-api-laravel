<?php

namespace Brunojsbr\EvolutionApi\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Brunojsbr\EvolutionApi\Facades\EvolutionApi;

class EvolutionApiController extends Controller
{
    /**
     * Lista todas as instâncias
     */
    public function listInstances(): JsonResponse
    {
        try {
            $instances = EvolutionApi::getInstances();
            return response()->json($instances);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Cria uma nova instância
     */
    public function createInstance(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string'
            ]);

            $instance = EvolutionApi::createInstance($validated['name']);
            return response()->json($instance);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtém o QR Code de uma instância
     */
    public function getInstanceQR(string $instance): JsonResponse
    {
        try {
            $qr = EvolutionApi::getInstanceQR($instance);
            return response()->json($qr);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Verifica o status de uma instância
     */
    public function getInstanceStatus(string $instance): JsonResponse
    {
        try {
            $status = EvolutionApi::getInstanceStatus($instance);
            return response()->json($status);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Desconecta uma instância
     */
    public function disconnectInstance(string $instance): JsonResponse
    {
        try {
            $result = EvolutionApi::disconnectInstance($instance);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Envia uma mensagem de texto
     */
    public function sendText(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'instance' => 'required|string',
                'to' => 'required|string',
                'message' => 'required|string'
            ]);

            $result = EvolutionApi::sendText(
                $validated['instance'],
                $validated['to'],
                $validated['message']
            );

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Envia uma imagem
     */
    public function sendImage(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'instance' => 'required|string',
                'to' => 'required|string',
                'url' => 'required|url',
                'caption' => 'nullable|string'
            ]);

            $result = EvolutionApi::sendImage(
                $validated['instance'],
                $validated['to'],
                $validated['url'],
                $validated['caption'] ?? null
            );

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Envia um documento
     */
    public function sendDocument(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'instance' => 'required|string',
                'to' => 'required|string',
                'url' => 'required|url',
                'filename' => 'required|string'
            ]);

            $result = EvolutionApi::sendDocument(
                $validated['instance'],
                $validated['to'],
                $validated['url'],
                $validated['filename']
            );

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Processa webhooks da Evolution API
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        try {
            // Valida o secret do webhook
            if ($request->header('X-API-Key') !== config('evolution-whatsapp.api.webhook_secret')) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Processa o evento
            $event = $request->input('event');
            $data = $request->input('data');

            // Dispara o evento correspondente
            event("evolution-whatsapp.{$event}", [$data]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
