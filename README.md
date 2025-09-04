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

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

MIT License - see LICENSE file for details.
*/

// composer.json dependencies to add:
/*
{
    "require": {
        "filament/filament": "^3.0",
        "intervention/image": "^2.7"
    }
}
*/
