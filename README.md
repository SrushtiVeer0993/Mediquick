# MediQuick - Healthcare Management System

MediQuick is a comprehensive healthcare management system that provides various medical services including emergency assistance, pharmacy services, symptom checking, and medical consultations.

## Features

- Emergency Services Management
- Pharmacy Integration
- Symptom Checker
- Medical Consultation Booking
- First Aid Information
- User Authentication System
- Admin Dashboard
- Chatbot Support

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer
- XAMPP (recommended for local development)

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
```

2. Navigate to the project directory:
```bash
cd mediquick
```

3. Install dependencies:
```bash
composer install
```

4. Create a MySQL database and import the schema:
```bash
mysql -u your_username -p your_database_name < database/schema.sql
```

5. Configure your web server to point to the project directory

6. Set up environment variables (create a .env file based on .env.example)

## Configuration

1. Database configuration is in `includes/config.php`
2. Mail settings can be configured in the admin panel
3. API keys and other sensitive data should be stored in .env file

### Google Maps API Setup

To use the Google Maps functionality in the pharmacy and emergency modules:

1. Go to the [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the following APIs:
   - Maps JavaScript API
   - Places API
   - Geocoding API
4. Create credentials (API key) for the enabled APIs
5. Add the API key to your `.env` file:
   ```
   GOOGLE_MAPS_API_KEY=your_actual_api_key_here
   ```
6. Restrict the API key to your domain for security

## Security

- All passwords are hashed using PHP's password_hash()
- CSRF protection implemented
- SQL injection prevention using prepared statements
- XSS protection implemented

## Directory Structure

```
mediquick/
├── admin/           # Admin panel files
├── api/            # API endpoints
├── assets/         # CSS, JS, images
├── database/       # Database schemas and migrations
├── includes/       # PHP includes and functions
├── vendor/         # Composer dependencies
└── [PHP files]     # Main application files
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please contact [support email/contact information] 