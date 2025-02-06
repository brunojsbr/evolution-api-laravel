#!/bin/bash

echo "Iniciando instalação do Evolution API WhatsApp Integration..."

# Verifica se o .env existe
if [ ! -f .env ]; then
    echo "Arquivo .env não encontrado!"
    exit 1
fi

# Adiciona as configurações da Evolution API no .env
echo "Configurando variáveis de ambiente..."
cat << EOF >> .env

# Evolution API Configuration
EVOLUTION_API_URL=https://api-v1.lv-suporte.cloud
EVOLUTION_API_KEY=a20e5ed8-b7e0-4e0c-a597-f9ad6c5bb67c
EVOLUTION_INSTANCE_NAME=\${APP_NAME}
EVOLUTION_WEBHOOK_SECRET=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9
EOF

# Instala o pacote via Composer
echo "Instalando o pacote..."
composer require brunojsbr/evolution-api-laravel

# Publica os assets
echo "Publicando assets..."
php artisan vendor:publish --provider="Brunojsbr\EvolutionApi\Providers\EvolutionApiServiceProvider" --force

# Executa as migrações
echo "Executando migrações..."
php artisan migrate

# Configura o webhook
echo "Configurando webhook..."
php artisan evolution:configure-webhook

# Limpa o cache
echo "Limpando cache..."
php artisan config:clear
php artisan cache:clear

# Adiciona o menu no layout
echo "Adicionando menu do WhatsApp..."
if [ -f resources/views/admin/layouts/app.blade.php ]; then
    # Procura pelo último </ul> no arquivo
    sed -i '/<\/ul>/i \
    <li class="nav-item">\
        <a href="{{ route('\''admin.whatsapp.index'\'') }}" class="nav-link {{ request()->routeIs('\''admin.whatsapp.*'\'') ? '\''active'\'' : '\'''\'' }}">\
            <i class="fab fa-whatsapp"></i>\
            <span>WhatsApp</span>\
        </a>\
    </li>' resources/views/admin/layouts/app.blade.php
fi

echo "Instalação concluída!"
echo "Por favor, verifique se:"
echo "1. As variáveis de ambiente foram configuradas corretamente"
echo "2. O menu do WhatsApp foi adicionado ao layout"
echo "3. As migrações foram executadas com sucesso"
echo "4. O webhook está funcionando corretamente"

# Testa a conexão
echo "Testando conexão com a Evolution API..."
php artisan evolution:test-connection
