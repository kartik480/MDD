# Managing Director App - Setup Instructions

## Database Setup (Hostinger)

### 1. Create Database
1. Log in to your Hostinger control panel
2. Go to "Databases" → "MySQL Databases"
3. Create a new database with the following details:
   - Database name: `u344026722_md`
   - Username: `u344026722_md`
   - Password: `zC$:KHc]iB2`

### 2. Create User Table
1. Go to phpMyAdmin in your Hostinger control panel
2. Select your database `u344026722_md`
3. Go to "SQL" tab
4. Run the following SQL query:

```sql
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample users
INSERT INTO `tbl_user` (`username`, `password`) VALUES 
('admin', 'admin123'),
('director', 'director123'),
('manager', 'manager123');
```

## Hosting Setup (Hostinger)

### 1. Upload API Files
1. Log in to your Hostinger File Manager
2. Navigate to `public_html/md/`
3. Create a folder called `api`
4. Upload the following files to the `api` folder:
   - `db_config.php`
   - `login.php`
   - `test_connection.php`

### 2. File Structure
Your hosting should have this structure:
```
public_html/
└── md/
    └── api/
        ├── db_config.php
        ├── login.php
        └── test_connection.php
```

### 3. Test API Endpoints
You can test the API endpoints using these URLs:
- Test connection: `http://emp.kfinone.com/mobile/api/test_connection.php`
- Login endpoint: `http://emp.kfinone.com/mobile/api/login.php`

## Flutter App Setup

### 1. Install Dependencies
Run the following command in your Flutter project:
```bash
flutter pub get
```

### 2. Test the App
1. Run the app: `flutter run`
2. Use these test credentials:
   - Username: `admin`, Password: `admin123`
   - Username: `director`, Password: `director123`
   - Username: `manager`, Password: `manager123`

## Troubleshooting

### Database Connection Issues
1. Verify database credentials in `api/db_config.php`
2. Check if the database exists in Hostinger
3. Ensure the `tbl_user` table is created

### API Connection Issues
1. Verify the API URLs in `lib/services/database_service.dart`
2. Check if the PHP files are uploaded correctly
3. Test the API endpoints directly in browser

### CORS Issues
The API files include CORS headers for Flutter web. If you're testing on mobile, this shouldn't be an issue.

## Security Notes
- In production, consider hashing passwords using bcrypt or similar
- Use HTTPS for all API calls
- Consider implementing rate limiting
- Add input validation and sanitization 