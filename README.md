<<<<<<< HEAD
# Talla Filament - Art Gallery Management System

A modern Laravel application built with Filament that provides an elegant interface for browsing, managing, and organizing artwork from the Art Institute of Chicago API. This application allows users to explore public domain artworks, save favorites, and manage their personal image collections.

## 🎨 Features

### Art Gallery
- **Browse Artworks**: Explore thousands of public domain artworks from the Art Institute of Chicago
- **Search Functionality**: Search through artworks by title, artist, or other metadata
- **High-Quality Images**: View artworks in high resolution with zoom capabilities
- **Responsive Design**: Beautiful, modern interface that works on all devices
- **Pagination**: Navigate through large collections efficiently

### Favorites System
- **Save Favorites**: Mark your favorite artworks for quick access
- **Favorites Management**: View and manage all your saved favorites in one place
- **Persistent Storage**: Favorites are saved to the database and persist across sessions

### Image Management
- **Upload Images**: Upload and manage your own image collections
- **Image Metadata**: Store title, description, and file information
- **File Management**: Automatic file cleanup and organization
- **Download Capability**: Download high-resolution images from the API

### Technical Features
- **API Integration**: Seamless integration with Art Institute of Chicago API
- **Caching**: Intelligent caching for improved performance
- **Error Handling**: Robust error handling and user feedback
- **Modern UI**: Built with Filament and Tailwind CSS for a premium experience

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x
- **Admin Panel**: Filament 4.0
- **Frontend**: Livewire, Alpine.js, Tailwind CSS
- **Database**: SQLite (configurable)
- **Build Tools**: Vite
- **API**: Art Institute of Chicago API

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18.x or higher
- **NPM**: Latest version
- **Git**: For version control

## 🚀 Installation

### 1. Clone the Repository
=======
# Talla Image Gallery - Laravel Filament Application

A comprehensive image gallery application built with Laravel and Filament that integrates with the Art Institute of Chicago API.

## Features

- 🎨 **Gallery Page**: Browse artworks from the Art Institute of Chicago API
- 📁 **My Images**: Upload and manage your own images (max 6MB)
- ❤️ **Favorites**: Save favorite images from both gallery and uploads
- 🔍 **Search**: Full-text search across all sections
- 📱 **Responsive**: Mobile-friendly interface
- 🌍 **Multi-language**: English and Arabic support
- ✨ **Interactive**: Alpine.js powered animations and interactions

## Tech Stack

- **Backend**: Laravel 10+
- **Admin Panel**: Filament 3.0
- **Frontend**: Alpine.js + Tailwind CSS
- **Database**: MySQL/SQLite
- **External API**: Art Institute of Chicago

## Installation

1. Clone the repository:
```bash
git clone <your-repo-url>
cd talla-image-gallery
```

2. Install dependencies:
```bash
composer install
npm install && npm run build
```

3. Environment setup:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=talla_gallery
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations:
```bash
php artisan migrate
```

6. Create storage link:
```bash
php artisan storage:link
```

7. Create admin user:
```bash
php artisan make:filament-user
```

8. Start the development server:
```bash
php artisan serve
```

Visit `http://localhost:8000/admin` to access the application.

## Usage

### Gallery Page
- Browse artworks from the Art Institute of Chicago
- Search for specific artworks
- Add/remove favorites
- Download high-resolution images
- Click images for full-size view

### My Images Page
- Upload images (max 6MB)
- Add titles and descriptions
- Mark as favorites
- Download your uploads
- Search through your collection

### Favorites Page
- View all favorited images
- Remove from favorites
- Download favorite images
- Search favorites

## API Integration

This application uses the Art Institute of Chicago API:
- Base URL: `https://api.artic.edu/api/v1/artworks`
- Image URLs: Constructed using IIIF standards
- Caching: API responses are cached for 5 minutes

## File Structure

```
app/
├── Filament/
│   ├── Pages/
│   │   ├── ImageGallery.php
│   │   └── Favorites.php
│   └── Resources/
│       └── ImageResource.php
├── Models/
│   ├── Image.php
│   └── ApiFavorite.php
└── Services/
    └── ArtInstituteApiService.php
```
>>>>>>> 0c0e86028c51eced0816035702f756a738bcd5a9

```bash
git clone <repository-url>
cd talla_filament
```

<<<<<<< HEAD
### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Configuration
=======
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request
>>>>>>> 0c0e86028c51eced0816035702f756a738bcd5a9

Copy the environment file and configure your settings:

<<<<<<< HEAD
```bash
cp .env.example .env
```

Edit the `.env` file with your configuration:

```env
APP_NAME="Talla Filament"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration (SQLite by default)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Cache Configuration
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Mail Configuration (optional)
MAIL_MAILER=log
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Database Setup

Create the SQLite database file and run migrations:

```bash
touch database/database.sqlite
php artisan migrate
```

### 7. Create Admin User

Create your first admin user:

```bash
php artisan make:filament-user
```

Follow the prompts to create your admin account.

### 8. Build Assets

Build the frontend assets:

```bash
npm run build
```

## 🏃‍♂️ Running the Application

### Development Mode

For development, you can use the convenient dev script that runs multiple services:

```bash
composer run dev
```

This command will start:
- Laravel development server (http://localhost:8000)
- Queue worker
- Log monitoring
- Vite development server

### Manual Setup

Alternatively, you can run services manually:

1. **Start Laravel Server**:
   ```bash
   php artisan serve
   ```

2. **Start Vite Dev Server** (in another terminal):
   ```bash
   npm run dev
   ```

3. **Start Queue Worker** (optional, in another terminal):
   ```bash
   php artisan queue:work
   ```

### Production Mode

For production deployment:

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📁 Project Structure

```
talla_filament/
├── app/
│   ├── Filament/
│   │   ├── Pages/           # Custom Filament pages
│   │   └── Resources/       # Filament resources
│   ├── Models/              # Eloquent models
│   │   ├── ApiFavorite.php  # API favorites model
│   │   ├── UploadedImage.php # Uploaded images model
│   │   └── User.php         # User model
│   └── services/
│       └── ApiService.php   # Art Institute API service
├── database/
│   ├── migrations/          # Database migrations
│   └── database.sqlite      # SQLite database
├── resources/
│   ├── views/
│   │   └── filament/        # Custom Filament views
│   ├── css/                 # Stylesheets
│   └── js/                  # JavaScript files
├── public/                  # Public assets
└── storage/                 # File storage
```

## 🎯 Usage

### Accessing the Application

1. Navigate to `http://localhost:8000/admin`
2. Log in with your admin credentials
3. Explore the different sections:
   - **Gallery**: Browse and search artworks
   - **Favorites**: Manage your saved artworks
   - **Uploaded Images**: Manage your uploaded images

### Key Features Usage

#### Browsing Artworks
- Use the search bar to find specific artworks
- Click on images to view them in full resolution
- Use pagination to browse through collections
- Click the heart icon to add/remove favorites

#### Managing Favorites
- All favorited artworks appear in the Favorites section
- Remove favorites by clicking the heart icon again
- Favorites persist across sessions

#### Uploading Images
- Use the file upload functionality to add your own images
- Provide titles and descriptions for better organization
- Mark images as favorites for quick access

## 🔧 Configuration

### API Configuration

The application uses the Art Institute of Chicago API. The API service is configured in `app/services/ApiService.php`:

- **Base URL**: `https://api.artic.edu/api/v1`
- **Image URL**: `https://www.artic.edu/iiif/2`
- **Caching**: 5 minutes for search results, 1 hour for public domain IDs

### Database Configuration

The application uses SQLite by default but can be configured for other databases:

```env
# For MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=talla_filament
DB_USERNAME=your_username
DB_PASSWORD=your_password

# For PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=talla_filament
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 🧪 Testing

Run the test suite:

```bash
composer run test
```

Or manually:

```bash
php artisan test
```

## 📦 Deployment

### Production Checklist

1. **Environment Configuration**:
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize Application**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```

3. **Build Assets**:
   ```bash
   npm run build
   ```

4. **Set Permissions**:
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

### Server Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- Web server (Apache/Nginx)
- SSL certificate (recommended)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

If you encounter any issues or have questions:

1. Check the [Laravel documentation](https://laravel.com/docs)
2. Check the [Filament documentation](https://filamentphp.com/docs)
3. Review the application logs in `storage/logs/laravel.log`
4. Create an issue in the repository

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/) - The PHP framework
- [Filament](https://filamentphp.com/) - The admin panel framework
- [Art Institute of Chicago](https://www.artic.edu/) - For providing the amazing API
- [Tailwind CSS](https://tailwindcss.com/) - For the beautiful styling

---

**Happy coding! 🎨✨**
=======
MIT License - see LICENSE file for details.
*/

// composer.json dependencies to add:
/*
{
    "require": {
        "filament/filament": "^4.0",
        "intervention/image": "^2.7"
    }
}
*/
>>>>>>> 0c0e86028c51eced0816035702f756a738bcd5a9
