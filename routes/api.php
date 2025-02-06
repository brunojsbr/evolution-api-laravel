<?php

use Illuminate\Support\Facades\Route;
use Brunojsbr\EvolutionApi\Controllers\EvolutionApiController;

Route::prefix(config('evolution-whatsapp.routes.prefix'))
    ->middleware(config('evolution-whatsapp.routes.middleware'))
    ->group(function () {
        // Rotas de gerenciamento de instâncias
        Route::get('/instances', [EvolutionApiController::class, 'listInstances']);
        Route::post('/instances', [EvolutionApiController::class, 'createInstance']);
        Route::get('/instances/{instance}/qr', [EvolutionApiController::class, 'getInstanceQR']);
        Route::get('/instances/{instance}/status', [EvolutionApiController::class, 'getInstanceStatus']);
        Route::delete('/instances/{instance}', [EvolutionApiController::class, 'disconnectInstance']);

        // Rotas de mensagens
        Route::post('/messages/text', [EvolutionApiController::class, 'sendText']);
        Route::post('/messages/image', [EvolutionApiController::class, 'sendImage']);
        Route::post('/messages/document', [EvolutionApiController::class, 'sendDocument']);
        Route::post('/messages/audio', [EvolutionApiController::class, 'sendAudio']);
        Route::post('/messages/video', [EvolutionApiController::class, 'sendVideo']);
        Route::post('/messages/location', [EvolutionApiController::class, 'sendLocation']);
        Route::post('/messages/contact', [EvolutionApiController::class, 'sendContact']);
        Route::post('/messages/button', [EvolutionApiController::class, 'sendButton']);
        Route::post('/messages/list', [EvolutionApiController::class, 'sendList']);

        // Rota do webhook
        Route::post('/webhook', [EvolutionApiController::class, 'handleWebhook']);
    });
