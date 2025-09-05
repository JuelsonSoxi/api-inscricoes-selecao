# 🎯 API Inscrições & Seleção

Uma API RESTful desenvolvida em Laravel para gestão de programas, candidatos e candidaturas, com sistema de autenticação usando Laravel Sanctum.

## 🚀 Tecnologias Utilizadas

- **Laravel 12** (PHP 8.3+)
- **MySQL** como banco de dados
- **Laravel Sanctum** para autenticação API
- **Eloquent ORM** para modelagem de dados

## 📋 Funcionalidades

### ✅ Sistema de Autenticação
- Registro de candidatos com validação de email único
- Login com geração de token JWT
- Logout com revogação de token
- Middleware de proteção para rotas privadas

### ✅ Gestão de Programas
- Listagem pública de programas (com filtros)
- Visualização detalhada de programas
- CRUD completo para programas (protegido)
- Filtros por status e disponibilidade

### ✅ Sistema de Candidaturas
- Submissão de candidaturas (apenas usuários autenticados)
- Validação completa das regras de negócio:
  - Programa deve estar ativo
  - Data atual deve estar no período de inscrições
  - Candidato não pode se inscrever duas vezes no mesmo programa
  - Respeita limite máximo de candidatos por programa
- Listagem de candidaturas com filtros
- Visualização das próprias candidaturas
- Atualização de status das candidaturas
- Cancelamento de candidatura

## 📊 Modelo de Dados

### Entidades
- **Users**: Candidatos do sistema
- **Programs**: Programas disponíveis para candidatura
- **Applications**: Candidaturas dos usuários aos programas

### Relacionamentos
- User ↔ Program (Many-to-Many através de Applications)
- User → Applications (One-to-Many)
- Program → Applications (One-to-Many)

## ⚙️ Instalação e Configuração

### Pré-requisitos
- PHP 8.3 ou superior
- Composer
- MySQL
- Git

### Passo a Passo

1. **Clone o repositório**
```bash
git clone https://github.com/JuelsonSoxi/api-inscricoes-selecao.git
cd inscricoes_selecao_api
```

2. **Instale as dependências**
```bash
composer install
```

3. **Configure o ambiente**
```bash
# Copie o arquivo de exemplo
cp .env.example .env

# Gere a chave da aplicação
php artisan key:generate
```

4. **Configure o banco de dados no arquivo `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inscricoes_selecao_api
DB_USERNAME=root
DB_PASSWORD=
```

5. **Execute as migrations e seeders**
```bash
# Rode as migrations
php artisan migrate

# Execute os seeders para dados de exemplo
php artisan db:seed
```

6. **Inicie o servidor**
```bash
php artisan serve
```

A API estará disponível em: `http://127.0.0.1:8000/api`

## 📡 Documentação da API

### Base URL
```
http://127.0.0.1:8000/api
```

### Autenticação
A API utiliza Bearer Token. Após o login, inclua o token no header:
```
Authorization: Bearer {seu_token}
```

### Endpoints Principais

#### 🔐 Autenticação
- `POST /auth/register` - Registrar novo usuário
- `POST /auth/login` - Fazer login
- `POST /auth/logout` - Fazer logout
- `GET /me` - Obter dados do usuário logado

#### 📚 Programas
- `GET /programs` - Listar programas
- `GET /programs?available=true` - Listar programas disponíveis
- `GET /programs/{id}` - Detalhes do programa
- `POST /programs` - Criar programa (protegido)
- `PUT /programs/{id}` - Atualizar programa (protegido)
- `DELETE /programs/{id}` - Deletar programa (protegido)

#### 📝 Candidaturas
- `POST /applications` - Submeter candidatura (protegido)
- `GET /my-applications` - Minhas candidaturas (protegido)
- `GET /applications` - Listar candidaturas (protegido)
- `GET /applications?status=pending` - Filtrar por status (protegido)
- `PUT /applications/{id}/status` - Atualizar status (protegido)
- `DELETE /applications/{id}` - Cancelar candidatura (protegido)

## 💾 Dados de Exemplo

### Usuários de Teste
```
Email: soxi@test.com  
Senha: 123456

Email: moises@test.com
Senha: 123456
```

### Programas Pré-cadastrados
- Programa de Estágio em Tecnologia
- Bootcamp de Desenvolvimento Web
- Programa de Mentoria em Startups
- Curso de Análise de Dados

## 📖 Como Testar

### 1. Importe a Coleção Postman
Importe o arquivo `postman_collection.json` no Postman para ter todos os endpoints pré-configurados.

### 2. Fluxo de Teste Básico

1. **Registre um usuário** via `POST /auth/register`
2. **Faça login** via `POST /auth/login` e guarde o token
3. **Liste os programas** via `GET /programs?available=true`
4. **Candidate-se** via `POST /applications`
5. **Verifique suas candidaturas** via `GET /my-applications`

### 3. Exemplo de Requisições

**Registrar usuário:**
```json
POST /api/auth/register
{
    "name": "Juelson Soxi",
    "email": "soxi@email.com",
    "password": "123456",
    "password_confirmation": "123456"
}
```

**Submeter candidatura:**
```json
POST /api/applications
Authorization: Bearer {token}
{
    "program_id": 1,
    "motivation": "Tenho grande interesse em tecnologia..."
}
```

## 🐛 Tratamento de Erros

A API retorna erros padronizados com códigos HTTP apropriados:

- `400` - Bad Request (dados inválidos)
- `401` - Unauthorized (não autenticado)
- `404` - Not Found (recurso não encontrado)
- `422` - Unprocessable Entity (validação falhou)
- `500` - Internal Server Error (erro interno)

Exemplo de resposta de erro:
```json
{
    "ok": false,
    "message": "Este programa não está disponível para candidaturas no momento"
}
```

## 📚 Regras de Negócio Implementadas

1. ✅ Candidato só pode se candidatar se estiver logado
2. ✅ Programa só aceita candidaturas quando:
   - Status = 'active'
   - Data atual entre start_date e end_date
3. ✅ Candidato não pode se candidatar duas vezes ao mesmo programa
4. ✅ Programa respeita limite máximo de candidatos (se definido)
5. ✅ Validação de email único no registro
6. ✅ Tokens de autenticação com Sanctum

## 🔧 Comandos Úteis

```bash
# Limpar cache
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Rodar migrations fresh
php artisan migrate:fresh --seed

# Verificar rotas
php artisan route:list
```

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

## 👨‍💻 Autor

Desenvolvido por Juelson Gonçalves Soxi - juelsongoncalvessoxi@email.com

---

⭐ Não esqueça de dar uma estrela no projeto se ele te ajudou!
