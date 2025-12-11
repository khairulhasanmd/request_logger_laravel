# 🪝 Webhook Capture - Request Logger

A Laravel-based request logging and inspection tool similar to webhook.site. Capture, analyze, and debug HTTP requests with a beautiful web interface.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat&logo=mysql)

## 📋 Table of Contents

- [Features](#features)
- [Screenshots](#screenshots)
- [Installation](#installation)
- [Usage](#usage)
- [How It Works](#how-it-works)
- [API Examples](#api-examples)
- [Configuration](#configuration)
- [License](#license)

## ✨ Features

- **🎯 Universal Request Capture** - Captures ALL GET, POST, PUT, DELETE, PATCH requests to any endpoint
- **📊 Dashboard View** - Beautiful dashboard showing request statistics grouped by endpoint and HTTP method
- **🔍 Detailed Inspection** - View complete request details including:
  - HTTP method and full URL
  - Query parameters
  - POST data / Request body
  - All headers
  - IP address and user agent
  - Server fingerprints
  - Timestamps
- **🗂️ Request Grouping** - Automatically groups requests by path and method with counts
- **📖 Individual Request View** - Click any request to see full details
- **🧹 Clear All Data** - One-click button to clear all captured requests
- **🎨 Modern UI** - Built with Tailwind CSS and Font Awesome icons
- **📱 Responsive Design** - Works on desktop, tablet, and mobile devices
- **💾 MySQL Database** - Persistent storage for all captured requests

## 🖼️ Screenshots

### Dashboard
View all captured endpoints with request counts

### Request Details
Inspect complete request information with JSON formatting

## 🚀 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (for asset compilation)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd webhook_capture
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Create MySQL database**
   ```bash
   mysql -u root -p
   CREATE DATABASE webhook CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   exit;
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Update `.env` file**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=webhook
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

8. **Access the application**
   - Dashboard: `http://localhost:8000/report`
   - Send requests to: `http://localhost:8000/any-endpoint`

## 📖 Usage

### Viewing the Dashboard

Navigate to `http://localhost:8000/report` to see all captured requests grouped by endpoint and HTTP method.

### Capturing Requests

Send HTTP requests to **any endpoint** on your domain. All requests (except `/report` routes) will be automatically captured:

```bash
# GET request with query parameters
curl "http://localhost:8000/api/test?foo=bar&user=john"

# POST request with JSON body
curl -X POST http://localhost:8000/webhook \
  -H "Content-Type: application/json" \
  -d '{"event":"user.created","id":123,"name":"John Doe"}'

# PUT request with form data
curl -X PUT http://localhost:8000/api/users/1 \
  -d "name=Jane&email=jane@example.com"

# Any other endpoint
curl http://localhost:8000/custom/path/anything
```

### Viewing Request Details

1. Click on any row in the dashboard table
2. See complete request information including headers, body, and metadata
3. Use the "Copy" buttons to copy JSON data to clipboard

### Clearing All Requests

Click the **"Clear All Requests"** button on the dashboard to delete all captured data.

## ⚙️ How It Works

### Architecture

1. **Middleware (`CaptureRequestMiddleware`)**
   - Intercepts all incoming requests
   - Excludes `/report` routes to prevent recursive logging
   - Extracts request data (method, URL, headers, body, etc.)
   - Stores data in MySQL database

2. **Model (`RequestLog`)**
   - Eloquent model for database operations
   - JSON casting for arrays (headers, query params, POST data)
   - Fillable attributes for mass assignment

3. **Controller (`RequestLogController`)**
   - `index()` - Dashboard with grouped statistics
   - `pathDetails()` - Detailed view of requests for a specific path
   - `show()` - Individual request details
   - `clear()` - Delete all captured requests

4. **Routes**
   - `/report` - Main dashboard
   - `/report/path` - Path-specific details
   - `/report/{id}` - Single request view
   - `/report/clear` - Clear all data
   - `{any}` - Catch-all route for capturing requests

### Database Schema

```sql
request_logs
  - id (primary key)
  - method (string)
  - url (text)
  - full_url (text)
  - path (string)
  - query_params (json)
  - post_data (json)
  - headers (json)
  - raw_body (text)
  - ip_address (string)
  - user_agent (string)
  - server_vars (json)
  - created_at (timestamp)
  - updated_at (timestamp)
```

## 🔧 API Examples

### Testing with cURL

```bash
# Simple GET request
curl http://localhost:8000/test

# GET with query parameters
curl "http://localhost:8000/api/users?page=1&limit=10&sort=name"

# POST with JSON
curl -X POST http://localhost:8000/api/webhook \
  -H "Content-Type: application/json" \
  -H "X-API-Key: secret123" \
  -d '{
    "event": "payment.success",
    "amount": 99.99,
    "currency": "USD",
    "user_id": 12345
  }'

# POST with form data
curl -X POST http://localhost:8000/forms/contact \
  -d "name=John Doe" \
  -d "email=john@example.com" \
  -d "message=Hello World"

# Custom headers
curl http://localhost:8000/api/test \
  -H "Authorization: Bearer token123" \
  -H "X-Custom-Header: custom-value"
```

### Testing with JavaScript

```javascript
// GET request
fetch('http://localhost:8000/api/test?id=123')
  .then(response => response.json())
  .then(data => console.log(data));

// POST request
fetch('http://localhost:8000/api/webhook', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-API-Key': 'secret123'
  },
  body: JSON.stringify({
    event: 'user.login',
    userId: 456,
    timestamp: new Date().toISOString()
  })
});
```

## 🔐 Configuration

### Excluding Specific Routes from Capture

Edit `app/Http/Middleware/CaptureRequestMiddleware.php`:

```php
// Skip capturing for specific routes
if (!str_starts_with($request->path(), 'report') && 
    !str_starts_with($request->path(), 'admin')) {
    // Capture logic here
}
```

### Customizing Response

Edit `routes/web.php` to customize the catch-all response:

```php
Route::any('{any}', function () {
    return response()->json([
        'status' => 'captured',
        'message' => 'Your custom message'
    ], 200);
})->where('any', '.*');
```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

## 👨‍💻 Author

Built with ❤️ using Laravel

---

**Note:** This tool is designed for development and testing purposes. For production use, consider adding authentication, rate limiting, and data retention policies.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
