# Lead Manager

Lead management system with integrated VoIP features.

## 🚀 Technologies

This project was developed with the following technologies:

- PHP 8.1
- Laravel 10.x
- MySQL
- Docker
- Twilio (for VoIP features)
- JWT for authentication

## 💻 Project

Lead Manager is a system for managing leads that allows:

- Client registration and management
- User authentication
- Integration with VoIP calls via Twilio
- Email notification system
- RESTful API

## 🔧 Requirements

- Docker
- Docker Compose
- Composer
- PHP >= 8.1

## 🎲 Running the project

```bash
# Clone this repository
git clone https://github.com/andersonRocha091/ontactManagerApi

# Access the project folder
cd lead_manager

# Copy the environment file
cp .env.example .env

# Install dependencies
composer install

# Start Docker containers
docker-compose up -d

# Run migrations
docker exec -it app php artisan migrate

# Generate application key
docker exec -it app php artisan key:generate

# Generate JWT key
docker exec -it app php artisan jwt:secret
```

## ⚙️ Configuration

### Environment Variables

Configure the following variables in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=lead_manager
DB_USERNAME=your_username
DB_PASSWORD=your_password

TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token
TWILIO_FROM=your_twilio_number

MAIL_MAILER=smtp
MAIL_HOST=<your smtp provider>
MAIL_PORT=PORT
MAIL_USERNAME=<your smtp user>
MAIL_PASSWORD=<your sempt password>

JWT_SECRET=<your jwt secret>
```

### Twilio

To configure Twilio:

1. Create an account on [Twilio](https://www.twilio.com)
2. Obtain your credentials (SID and Token)
3. Configure a phone number
4. Add the credentials to the `.env` file

## 📝 API Endpoints

### Autenticação

#### Registrar Usuário
```http
POST /api/register

Request:
{
    "name": "João Silva",
    "email": "joao@email.com",
    "password": "senha123",
    "password_confirmation": "senha123"
}

Response: (201 Created)
{
    "status": "success",
    "message": "Usuário registrado com sucesso",
    "data": {
        "id": 1,
        "name": "João Silva",
        "email": "joao@email.com",
        "created_at": "2024-03-04T10:00:00.000000Z"
    }
}
```

#### Login
```http
POST /api/login

Request:
{
    "email": "joao@email.com",
    "password": "senha123"
}

Response: (200 OK)
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
}
```

#### Logout
```http
POST /api/logout

Headers:
Authorization: Bearer {token}

Response: (200 OK)
{
    "message": "Deslogado com sucesso"
}
```

### Clientes

#### Listar Clientes
```http
GET /api/client

Headers:
Authorization: Bearer {token}

Response: (200 OK)
{
    "data": [
        {
            "id": 1,
            "name": "Maria Santos",
            "email": "maria@email.com",
            "phone": "11999999999",
            "address": "Rua das Flores, 123",
            "city": "São Paulo",
            "state": "SP",
            "created_at": "2024-03-04T10:00:00.000000Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "total": 10,
        "per_page": 15
    }
}
```

#### Obter Cliente
```http
GET /api/client/{id}

Headers:
Authorization: Bearer {token}

Response: (200 OK)
{
    "data": {
        "id": 1,
        "name": "Maria Santos",
        "email": "maria@email.com",
        "phone": "11999999999",
        "address": "Rua das Flores, 123",
        "city": "São Paulo",
        "state": "SP",
        "created_at": "2024-03-04T10:00:00.000000Z"
    }
}
```

#### Criar Cliente
```http
POST /api/client

Headers:
Authorization: Bearer {token}

Request:
{
    "name": "Pedro Oliveira",
    "email": "pedro@email.com",
    "phone": "11988888888",
    "address": "Av. Paulista, 1000",
    "city": "São Paulo",
    "state": "SP",
    "zip": "01310-100",
    "age": 30
}

Response: (201 Created)
{
    "data": {
        "id": 2,
        "name": "Pedro Oliveira",
        "email": "pedro@email.com",
        "phone": "11988888888",
        "address": "Av. Paulista, 1000",
        "city": "São Paulo",
        "state": "SP",
        "zip": "01310-100",
        "age": 30,
        "created_at": "2024-03-04T11:00:00.000000Z"
    }
}
```

#### Atualizar Cliente
```http
PUT /api/client/{id}

Headers:
Authorization: Bearer {token}

Request:
{
    "name": "Pedro Silva Oliveira",
    "phone": "11977777777"
}

Response: (200 OK)
{
    "data": {
        "id": 2,
        "name": "Pedro Silva Oliveira",
        "email": "pedro@email.com",
        "phone": "11977777777",
        "address": "Av. Paulista, 1000",
        "city": "São Paulo",
        "state": "SP",
        "updated_at": "2024-03-04T12:00:00.000000Z"
    }
}
```

#### Remover Cliente
```http
DELETE /api/client/{id}

Headers:
Authorization: Bearer {token}

Response: (204 No Content)
```

### VoIP

#### Gerar Token
```http
POST /api/voip/token

Headers:
Authorization: Bearer {token}

Response: (200 OK)
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "expires_in": 3600
}
```

#### Iniciar Chamada
```http
POST /api/voip/call

Headers:
Authorization: Bearer {token}

Request:
{
    "to": "+5511999999999",
    "from": "+5511988888888"
}

Response: (200 OK)
{
    "status": "iniciado",
    "sid": "CA123456789",
    "message": "Chamada iniciada com sucesso"
}
```

### Códigos de Erro

```http
400 Bad Request
{
    "message": "Dados inválidos",
    "errors": {
        "email": ["O campo email é obrigatório"],
        "phone": ["O telefone deve ser um número válido"]
    }
}

401 Unauthorized
{
    "message": "Não autorizado"
}

404 Not Found
{
    "message": "Cliente não encontrado"
}

422 Unprocessable Entity
{
    "message": "Dados inválidos",
    "errors": {
        "email": ["Este email já está em uso"]
    }
}

500 Internal Server Error
{
    "message": "Erro interno do servidor"
}
```

## 🧪 Tests

To run the tests:

```bash
# Run all tests
docker exec -it app bash -c "cd /var/www/leadmanager  && php artisan test --filter=TestName"

# Run specific tests
docker exec -it app php artisan test --filter=TestName
```

## 📦 Project Structure

```
lead_manager/
├── app/
│   ├── Domains/
│   │   ├── Auth/
│   │   ├── Client/
│   │   ├── Shared/
│   │   └── Voip/
│   ├── Http/
│   └── Providers/
├── config/
├── database/
├── routes/
├── tests/
└── docker/
```

## 🤝 Contributing

1. Fork the project
2. Create a branch for your feature (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE.md) file for more details.

## 👥 Authors

- Your Name - [GitHub](https://github.com/andersonRocha091)

---

Made with ♥ by Your Name