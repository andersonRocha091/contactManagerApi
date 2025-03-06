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
git clone https://github.com/andersonRocha091

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
```

### Twilio

To configure Twilio:

1. Create an account on [Twilio](https://www.twilio.com)
2. Obtain your credentials (SID and Token)
3. Configure a phone number
4. Add the credentials to the `.env` file

## 📝 API Endpoints

### Authentication

```
POST /api/register - Register a new user
POST /api/login - Login
POST /api/logout - Logout
GET /api/user - Get authenticated user data
```

### Clients

```
GET /api/client - List all clients
GET /api/client/{id} - Get a specific client
POST /api/client - Create a new client
PUT /api/client/{id} - Update a client
DELETE /api/client/{id} - Remove a client
```

### VoIP

```
POST /api/voip/token - Generate token for calls
POST /api/voip/call - Initiate a call
```

## 🧪 Tests

To run the tests:

```bash
# Run all tests
docker exec -it app php artisan test

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