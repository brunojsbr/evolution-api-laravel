#!/bin/bash

echo "Configurando ambiente de desenvolvimento local para Evolution API WhatsApp Integration..."

# Cria a estrutura de pastas para o pacote
mkdir -p packages/brunojsbr/evolution-api-laravel
cd packages/brunojsbr/evolution-api-laravel

# Inicializa o git
git init

# Cria a estrutura básica do pacote
mkdir -p src/Console
mkdir -p src/Controllers
mkdir -p src/Events
mkdir -p src/Facades
mkdir -p src/Models
mkdir -p src/Providers
mkdir -p src/Services
mkdir -p config
mkdir -p database/migrations
mkdir -p resources/views
mkdir -p routes

# Cria o composer.json do pacote
cat << EOF > composer.json
{
    "name": "brunojsbr/evolution-api-laravel",
    "description": "Integração Laravel com Evolution API para WhatsApp",
    "type": "library",
    "license": "MIT",
    "autoload": {
        "psr-4": {
            "Brunojsbr\\EvolutionApi\\": "src/"
        }
    },
    "authors": [
        {
            "name": "Bruno JS BR",
            "email": "seu-email@dominio.com"
        }
    ],
    "require": {
        "php": "^8.1",
        "illuminate/support": "^9.0|^10.0",
        "guzzlehttp/guzzle": "^7.0"
    },
    "extra": {
        "laravel": {
            "providers": [
                "Brunojsbr\\EvolutionApi\\Providers\\EvolutionApiServiceProvider"
            ],
            "aliases": {
                "EvolutionApi": "Brunojsbr\\EvolutionApi\\Facades\\EvolutionApi"
            }
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
EOF

# Volta para a raiz do projeto
cd ../../..

# Adiciona o repositório local no composer.json principal
composer config repositories.local '{"type": "path", "url": "packages/brunojsbr/evolution-api-laravel"}' --file composer.json

# Instala o pacote localmente
composer require brunojsbr/evolution-api-laravel:@dev

echo "Ambiente de desenvolvimento local configurado!"
echo "O pacote está em: packages/brunojsbr/evolution-api-laravel"
echo ""
echo "Próximos passos:"
echo "1. Implemente as classes no diretório src/"
echo "2. Teste as funcionalidades localmente"
echo "3. Quando estiver pronto, publique no GitHub e Packagist"
echo ""
echo "Para reinstalar o pacote após alterações:"
echo "composer update brunojsbr/evolution-api-laravel"
