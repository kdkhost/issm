#!/bin/bash
# =============================================================================
# Script de Deploy para cPanel/WHM - ISSM
# Instituto Socioambiental Serra do Mendanha
# =============================================================================
# Uso: Execute este script no servidor após fazer upload dos arquivos
# =============================================================================

echo "=========================================="
echo "  Deploy ISSM - Instituto Socioambiental"
echo "  Serra do Mendanha"
echo "=========================================="

# Verificar se está no diretório correto
if [ ! -f "artisan" ]; then
    echo "ERRO: Execute este script na raiz do projeto Laravel!"
    exit 1
fi

echo ""
echo "[1/8] Instalando dependências do Composer (sem dev)..."
composer install --optimize-autoloader --no-dev

echo ""
echo "[2/8] Gerando chave da aplicação (se necessário)..."
if grep -q "APP_KEY=$" .env || grep -q "APP_KEY=base64:$" .env; then
    php artisan key:generate
else
    echo "Chave já configurada."
fi

echo ""
echo "[3/8] Executando migrations..."
php artisan migrate --force

echo ""
echo "[4/8] Executando seeders (dados iniciais)..."
php artisan db:seed --force

echo ""
echo "[5/8] Criando link simbólico de storage..."
php artisan storage:link

echo ""
echo "[6/8] Limpando e otimizando caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo ""
echo "[7/8] Configurando permissões..."
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage/logs
find storage -type d -exec chmod 755 {} \;
find storage -type f -exec chmod 644 {} \;

echo ""
echo "[8/8] Verificando instalação..."
php artisan about 2>/dev/null | head -20

echo ""
echo "=========================================="
echo "  Deploy concluído com sucesso!"
echo "=========================================="
echo ""
echo "PRÓXIMOS PASSOS:"
echo "1. Configure o .env com as credenciais corretas do banco de dados"
echo "2. Configure o APP_URL com o domínio do site"
echo "3. Aponte o DocumentRoot para a pasta 'public'"
echo "4. Acesse /login para entrar no painel admin"
echo "   E-mail: admin@issm.org.br"
echo "   Senha: Admin@ISSM2024!"
echo "   (ALTERE A SENHA APÓS O PRIMEIRO LOGIN!)"
echo ""
