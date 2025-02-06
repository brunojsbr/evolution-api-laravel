# Guia de Implementação - Evolution API WhatsApp Integration

## Índice
1. [Pré-requisitos](#pré-requisitos)
2. [Instalação](#instalação)
   - [Instalação Automatizada](#instalação-automatizada)
   - [Instalação Manual](#instalação-manual)
3. [Configuração](#configuração)
4. [Uso Básico](#uso-básico)
5. [Funcionalidades Avançadas](#funcionalidades-avançadas)
6. [Troubleshooting](#troubleshooting)
7. [Desenvolvimento](#desenvolvimento)

## Pré-requisitos

- Laravel 9.0 ou superior
- PHP 8.1 ou superior
- Evolution API instalada e configurada
- Banco de dados MySQL 5.7+ ou PostgreSQL 9.6+
- Extensões PHP: `ext-json`, `ext-curl`
- Composer

## Instalação

### Instalação Automatizada

1. Baixe o script de instalação:
```bash
curl -O https://raw.githubusercontent.com/brunojsbr/evolution-api-laravel/main/install-evolution.sh
```

2. Dê permissão de execução:
```bash
chmod +x install-evolution.sh
```

3. Execute o script:
```bash
./install-evolution.sh
```

O script irá:
- Configurar as variáveis de ambiente
- Instalar o pacote via Composer
- Publicar os assets
- Executar as migrações
- Configurar o webhook
- Adicionar o menu no layout admin
- Testar a conexão

### Instalação Manual

1. Instale o pacote via Composer:
```bash
composer require brunojsbr/evolution-api-laravel
```

2. Adicione as variáveis ao seu `.env`:
```env
EVOLUTION_API_URL=sua_url_aqui
EVOLUTION_API_KEY=sua_chave_aqui
EVOLUTION_INSTANCE_NAME=nome_padrao_instancia
EVOLUTION_WEBHOOK_SECRET=seu_secret_aqui
```

3. Publique os assets:
```bash
php artisan vendor:publish --provider="Brunojsbr\EvolutionApi\Providers\EvolutionApiServiceProvider"
```

4. Execute as migrações:
```bash
php artisan migrate
```

5. Configure o webhook:
```bash
php artisan evolution:configure-webhook
```

## Desenvolvimento

Para desenvolver e contribuir com o pacote:

1. Clone o repositório:
```bash
git clone https://github.com/brunojsbr/evolution-api-laravel.git
```

2. Instale as dependências:
```bash
composer install
```

3. Configure o ambiente de desenvolvimento:
```bash
chmod +x setup-local-package.sh
./setup-local-package.sh
```

O script irá:
- Criar a estrutura do pacote
- Configurar o composer.json
- Preparar o ambiente para desenvolvimento
- Instalar o pacote localmente

4. Desenvolva no diretório:
```
packages/brunojsbr/evolution-api-laravel/
```

5. Para atualizar após alterações:
```bash
composer update brunojsbr/evolution-api-laravel
```

## Configuração

1. Adicione o menu do WhatsApp ao seu layout admin (`resources/views/admin/layouts/app.blade.php`):
```html
<div class="nav-item">
    <a href="{{ route('admin.whatsapp.index') }}" class="nav-link {{ request()->routeIs('admin.whatsapp.*') ? 'active' : '' }}">
        <i class="fab fa-whatsapp"></i>
        <span>WhatsApp</span>
    </a>
</div>
```

## Uso Básico

### 1. Gerenciamento de Instâncias

```php
use BrunoDevBr\EvolutionApi\Facades\EvolutionApi;

// Criar uma nova instância
$instance = EvolutionApi::createInstance('minha-instancia');

// Obter QR Code
$qrcode = EvolutionApi::getInstanceQR('minha-instancia');

// Verificar status
$status = EvolutionApi::getInstanceStatus('minha-instancia');

// Desconectar instância
EvolutionApi::disconnectInstance('minha-instancia');
```

### 2. Envio de Mensagens

```php
// Enviar texto
EvolutionApi::sendText('minha-instancia', '5511999999999', 'Olá! Como posso ajudar?');

// Enviar imagem
EvolutionApi::sendImage('minha-instancia', '5511999999999', 'https://url-da-imagem.jpg', 'Legenda opcional');

// Enviar documento
EvolutionApi::sendDocument('minha-instancia', '5511999999999', 'https://url-do-documento.pdf', 'Nome do arquivo');
```

### 3. Recebimento de Mensagens

Crie um listener para processar mensagens recebidas:

```php
use BrunoDevBr\EvolutionApi\Events\MessageReceived;

class WhatsAppMessageListener
{
    public function handle(MessageReceived $event)
    {
        $message = $event->message;
        $chat = $event->chat;

        // Seu código aqui
    }
}
```

Registre o listener em `EventServiceProvider.php`:
```php
protected $listen = [
    MessageReceived::class => [
        WhatsAppMessageListener::class,
    ],
];
```

## Funcionalidades Avançadas

### 1. WebSocket para Status em Tempo Real

```javascript
// resources/js/whatsapp-status.js
import Echo from 'laravel-echo';

window.Echo.private('whatsapp.instance.${instanceId}')
    .listen('InstanceStatusChanged', (e) => {
        console.log(e.status);
        updateStatusUI(e.status);
    });
```

### 2. Integração com Sistema de Leads

```php
use BrunoDevBr\EvolutionApi\Events\MessageReceived;

class WhatsAppLeadProcessor
{
    public function handle(MessageReceived $event)
    {
        $phone = $event->message->from;

        // Criar ou atualizar lead
        $lead = Lead::firstOrCreate(['phone' => $phone]);

        // Associar mensagem ao lead
        $event->chat->update(['lead_id' => $lead->id]);
    }
}
```

### 3. Armazenamento de Mídia

O pacote automaticamente gerencia o armazenamento de mídia recebida:
- Imagens: `storage/app/public/whatsapp/images`
- Documentos: `storage/app/public/whatsapp/documents`
- Áudios: `storage/app/public/whatsapp/audio`

Configure o disco de armazenamento em `config/evolution-whatsapp.php`:
```php
'storage' => [
    'disk' => 'public',
    'path' => 'whatsapp'
]
```

## Troubleshooting

### Problemas Comuns

1. **QR Code não aparece**
   - Verifique se a URL da API está correta
   - Confirme se a instância está iniciada
   - Verifique os logs em `storage/logs/evolution-whatsapp.log`

2. **Webhook não recebe eventos**
   - Verifique se o webhook está configurado corretamente na Evolution API
   - Confirme se a URL do webhook é acessível publicamente
   - Verifique se o secret está configurado corretamente

3. **Mensagens não são enviadas**
   - Verifique se a instância está conectada
   - Confirme se o número está formatado corretamente (DDI+DDD+Número)
   - Verifique os logs de erro

### Logs

O pacote mantém logs específicos em:
```
storage/logs/evolution-whatsapp.log
```

Para habilitar logs detalhados, ajuste em `config/evolution-whatsapp.php`:
```php
'debug' => true
```

### Comandos Úteis

```bash
# Listar todas as instâncias
php artisan evolution:list-instances

# Reconectar todas as instâncias
php artisan evolution:reconnect-all

# Limpar cache de status
php artisan evolution:clear-cache

# Testar conexão com API
php artisan evolution:test-connection
```

## Suporte

Para suporte, entre em contato através de:
- Email: seu-email@dominio.com
- GitHub Issues: [link-do-repositorio/issues](https://github.com/seu-repo/issues)

## Atualizações

Para atualizar o pacote:
```bash
composer update bruno_dev_br/evolution-api-laravel
php artisan migrate
php artisan evolution:update-hooks
```

## Contribuindo

Contribuições são bem-vindas! Por favor, leia nosso guia de contribuição antes de enviar pull requests.
