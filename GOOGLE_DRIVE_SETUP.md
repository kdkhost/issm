# Configuracao da Integracao Google Drive - Portal da Transparencia

Esta integracao permite que o Portal da Transparencia sincronize documentos automaticamente a partir de uma pasta no Google Drive da empresa.

## Requisitos

- Conta Google (gratuita)
- Acesso ao Google Cloud Console (gratuito)
- Pasta no Google Drive da empresa compartilhada com a Service Account

## Passo a passo

### 1. Criar projeto no Google Cloud Console

1. Acesse: https://console.cloud.google.com
2. Clique no seletor de projeto (topo) e em "Novo Projeto"
3. De um nome ao projeto (ex: `Portal Transparencia ISSM`) e clique em "Criar"

### 2. Habilitar a Google Drive API

1. No menu lateral, va em **APIs e Servicos > Biblioteca**
2. Pesquise por **Google Drive API**
3. Clique em **Ativar**

### 3. Criar Service Account

1. Va em **APIs e Servicos > Credenciais**
2. Clique em **+ Criar credenciais > Conta de servico**
3. Preencha:
   - Nome da conta: `portal-transparencia`
   - ID da conta: aceite o sugerido
   - Descricao: opcional
4. Clique em **Criar e continuar**
5. Em "Conceder acesso", ignore por enquanto e clique em **Concluir**

### 4. Gerar chave JSON

1. Na lista de Contas de Servico, clique na conta criada
2. Va na aba **Chaves**
3. Clique em **Adicionar chave > Criar nova chave**
4. Selecione **JSON** e clique em **Criar**
5. O arquivo sera baixado automaticamente. **Guarde com seguranca.**

### 5. Fazer upload da chave no painel administrativo

1. Acesse o **painel admin** do sistema
2. No menu lateral, clique em **Configuracoes** (icone de engrenagem)
3. Na pagina de configuracoes, clique na aba **Google Drive** na sidebar esquerda
4. Na secao **Credenciais Google Cloud**, clique em **Selecionar arquivo JSON** e envie o arquivo JSON baixado
5. O sistema salvara o arquivo automaticamente em `storage/app/google/credentials.json`

> Dica: voce tambem pode criar a pasta `storage/app/google/` manualmente e colocar o arquivo `credentials.json` diretamente, se preferir.

### 6. Compartilhar a pasta do Drive

1. Va no Google Drive da empresa e localize a pasta raiz que contem os documentos de transparencia
2. Clique com o botao direito na pasta > **Compartilhar**
3. Adicione o **e-mail da Service Account** (ex: `portal-transparencia@seu-projeto.iam.gserviceaccount.com`)
4. Defina a permissao como **Leitor**
5. Clique em **Enviar**

> **Importante:** a Service Account nao e um usuario comum do Gmail. O e-mail dela esta no JSON baixado (campo `client_email`) ou na pagina de detalhes da conta no Cloud Console.

### 7. Obter o ID da pasta raiz

1. No Google Drive, abra a pasta raiz dos documentos
2. O ID da pasta esta na URL do navegador, depois de `/folders/`:
   ```
   https://drive.google.com/drive/folders/1aBcD2eFgHiJkLmN3oPqRsTuVwXyZ4
   ```
   Neste exemplo, o ID e: `1aBcD2eFgHiJkLmN3oPqRsTuVwXyZ4`

### 8. Configurar pelo painel administrativo

1. Acesse o **painel admin** do sistema
2. No menu lateral, clique em **Configuracoes** (icone de engrenagem)
3. Na pagina de configuracoes, clique na aba **Google Drive** na sidebar esquerda
4. Ative o toggle **Ativar integracao com Google Drive**
5. No campo **ID da pasta raiz no Google Drive**, cole o ID da pasta obtido no passo 7
6. Clique em **Salvar Configuracoes**

> **Importante:** as configuracoes sao salvas no banco de dados (tabela `settings`), nao no `.env`.

### 9. Rodar a migration

Se o projeto ja estiver com o banco configurado, execute:

```bash
php artisan migrate
```

Isso adicionara os campos necessarios (`google_drive_file_id`, `google_drive_url`, `source`) na tabela `transparency_documents` e criara as entradas de configuracao na tabela `settings`.

### 10. Sincronizar documentos

Execute o comando de sincronizacao:

```bash
php artisan transparency:sync-drive
```

Para simular sem alterar o banco (dry-run):

```bash
php artisan transparency:sync-drive --dry-run
```

## Estrutura esperada no Google Drive

A pasta raiz configurada no campo **ID da pasta raiz no Google Drive** deve conter **subpastas**. Cada subpasta sera tratada como uma **categoria** no portal.

Exemplo:

```
Portal Transparencia (pasta raiz - GOOGLE_DRIVE_FOLDER_ID)
  |-- 2025 - Financeiro
  |     |-- Balancete_Janeiro.pdf
  |     |-- Balancete_Fevereiro.pdf
  |-- 2025 - Administrativo
  |     |-- Ata_Reuniao_01.pdf
  |     |-- Ata_Reuniao_02.pdf
  |-- 2024 - Relatorios
        |-- Relatorio_Anual_2024.pdf
```

- O nome da subpasta vira a **categoria** (ex: `2025 - Financeiro`)
- O sistema tenta extrair o **ano** do nome da pasta (busca `20XX`). Se nao encontrar, usa o ano atual.
- Cada arquivo dentro da subpasta vira um documento no portal.

## Automatizacao (opcional)

Para sincronizar automaticamente a cada X tempo, adicione ao **cron** do servidor (ex: a cada 1 hora):

```bash
0 * * * * cd /caminho/do/projeto && php artisan transparency:sync-drive >> /dev/null 2>&1
```

No Windows (Agendador de Tarefas), crie uma tarefa que execute:

```powershell
cd "G:\Tudo\MEU-SISTEMA\ISSM"
php artisan transparency:sync-drive
```

## Funcionamento

- Documentos **criados no Drive** aparecerao automaticamente no portal apos a sincronizacao.
- Documentos **removidos do Drive** serao desativados (`active = false`) no banco, sumindo do portal sem perder o historico.
- Documentos **editados no Drive** (nome alterado) serao atualizados no portal.
- Documentos cadastrados **manualmente pelo admin** (`source = local`) continuam funcionando normalmente e nao sao afetados pela sincronizacao.
