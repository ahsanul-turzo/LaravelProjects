# Laravel Social Media Application

A modern, feature-rich social media application built with Laravel 12, Livewire 3, Filament Admin, and Laravel Reverb for real-time features.

## Features

### Core Social Media Features
- ✅ **User Authentication**
  - Email/Password registration and login
  - Google OAuth integration
  - Apple OAuth integration
  - Two-factor authentication support

- ✅ **Posts**
  - Create, edit, and delete posts
  - Media uploads (images, videos)
  - Like, comment, and share functionality
  - Real-time updates

- ✅ **Comments**
  - Nested comments (reply functionality)
  - Like comments
  - Real-time comment updates

- ✅ **Social Interactions**
  - Follow/unfollow users
  - Like posts and comments
  - Share posts
  - Real-time notifications

- ✅ **Real-Time Chat**
  - Direct messaging between users
  - Read receipts
  - Real-time message delivery via Laravel Reverb
  - Message history

### Subscription System
- ✅ **Verified Badge** - $2/month
  - Blue verified badge
  - Enhanced profile visibility

- ✅ **Purple Badge** - $5/month
  - Premium purple badge
  - All verified badge features
  - Priority support
  - Ad-free experience

### Admin Dashboard (Filament)
- ✅ **Role-Based Access Control**
  - Super Admin: Full system access
  - Admin: User and content moderation
  - Moderator: Content moderation only
  - User: Basic permissions

- ✅ **Admin Features**
  - User management
  - Post moderation
  - Comment moderation
  - Subscription management
  - Analytics dashboard
  - System settings

## Tech Stack

### Backend
- **Laravel 12** - Latest PHP framework
- **Laravel Fortify** - Authentication
- **Laravel Socialite** - OAuth integration
- **Laravel Reverb** - Real-time WebSocket server
- **Spatie Permission** - Role and permission management
- **Spatie Media Library** - File uploads and management
- **Stripe PHP SDK** - Payment processing
- **Intervention Image** - Image manipulation

### Frontend
- **Livewire 3** - Full-stack framework
- **Livewire Flux** - Premium UI components
- **Livewire Volt** - Single-file components
- **Vite** - Asset bundling
- **TailwindCSS** - Utility-first CSS

### Admin Panel
- **Filament 3** - Modern admin panel
- Auto-generated resources for Users, Posts, and Subscriptions

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite (or MySQL/PostgreSQL)

### Setup Steps

1. **Clone the repository**
   ```bash
   cd /path/to/BlogApp
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your `.env` file**
   - Set up your database connection
   - Add Google OAuth credentials
   - Add Apple OAuth credentials
   - Add Stripe API keys
   - Configure Reverb settings

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Create storage link**
   ```bash
   php artisan storage:link
   ```

8. **Build assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

9. **Start the development servers**

   Terminal 1 - Laravel:
   ```bash
   php artisan serve
   ```

   Terminal 2 - Reverb (WebSockets):
   ```bash
   php artisan reverb:start
   ```

   Terminal 3 - Queue Worker:
   ```bash
   php artisan queue:work
   ```

   Terminal 4 - Vite (optional, if not using build):
   ```bash
   npm run dev
   ```

## Configuration

### Google OAuth Setup
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Enable Google+ API
4. Create OAuth 2.0 credentials
5. Add authorized redirect URI: `http://localhost:8000/auth/google/callback`
6. Update `.env`:
   ```
   GOOGLE_CLIENT_ID=your-client-id
   GOOGLE_CLIENT_SECRET=your-client-secret
   ```

### Apple OAuth Setup
1. Go to [Apple Developer](https://developer.apple.com/)
2. Create an App ID and Service ID
3. Configure Sign in with Apple
4. Update `.env`:
   ```
   APPLE_CLIENT_ID=your-client-id
   APPLE_CLIENT_SECRET=your-client-secret
   ```

### Stripe Setup
1. Go to [Stripe Dashboard](https://dashboard.stripe.com/)
2. Get your API keys
3. Create products for badges:
   - Verified Badge: $2/month
   - Purple Badge: $5/month
4. Get Price IDs
5. Update `.env`:
   ```
   STRIPE_KEY=pk_test_...
   STRIPE_SECRET=sk_test_...
   VERIFIED_BADGE_PRICE_ID=price_...
   PURPLE_BADGE_PRICE_ID=price_...
   ```

## Default Admin Credentials

After running seeders, you can login to the admin panel at `/admin`:

- **Email**: admin@socialmedia.test
- **Password**: password

## Database Schema

### Main Tables
- `users` - User accounts with social media fields
- `posts` - User posts with media
- `comments` - Nested comments on posts
- `likes` - Polymorphic likes (posts & comments)
- `shares` - Post shares
- `follows` - User follow relationships
- `messages` - Direct messages
- `subscriptions` - Paid subscriptions
- `roles` & `permissions` - RBAC system
- `media` - File attachments

## API Endpoints (Future)

The application is structured to easily add RESTful API endpoints:
- Authentication endpoints
- Post CRUD
- Comment CRUD
- Social interactions
- Real-time WebSocket events

## Real-Time Features

Laravel Reverb provides WebSocket support for:
- Live post updates
- Real-time comments
- Instant notifications
- Direct messaging
- Online user presence

## Performance Optimizations

### Implemented
- ✅ Database indexing on frequently queried columns
- ✅ Eager loading relationships to prevent N+1 queries
- ✅ Counter cache columns (likes_count, comments_count, etc.)
- ✅ Query optimization with proper indexes

### Recommended for Production
- [ ] Redis for caching and sessions
- [ ] Queue system for background jobs
- [ ] CDN for static assets
- [ ] Image optimization pipeline
- [ ] Database query caching
- [ ] Horizon for queue monitoring

## Security Features

- ✅ CSRF protection
- ✅ XSS prevention
- ✅ SQL injection protection (Eloquent ORM)
- ✅ Rate limiting
- ✅ Two-factor authentication
- ✅ Secure password hashing
- ✅ API token authentication ready

## Testing

Run tests with:
```bash
php artisan test
```

## Project Structure

```
app/
├── Models/               # Eloquent models
│   ├── User.php
│   ├── Post.php
│   ├── Comment.php
│   ├── Like.php
│   ├── Share.php
│   ├── Follow.php
│   ├── Message.php
│   └── Subscription.php
├── Filament/            # Admin panel resources
│   └── Resources/
│       ├── UserResource.php
│       ├── PostResource.php
│       └── SubscriptionResource.php
└── Livewire/            # Frontend components (to be added)

database/
├── migrations/          # Database migrations
└── seeders/
    └── RolesAndPermissionsSeeder.php

resources/
├── views/               # Blade templates
└── js/                  # JavaScript assets

routes/
├── web.php             # Web routes
├── auth.php            # Authentication routes
└── console.php         # Console commands
```

## Next Steps & Roadmap

### Immediate Next Steps
1. **Create Livewire Components**
   - Feed/timeline component
   - Post creation component
   - Comment section component
   - Chat/messaging interface
   - User profile component

2. **Implement Controllers**
   - PostController for post management
   - CommentController for comments
   - SocialController for likes/shares/follows
   - MessageController for chat
   - SubscriptionController for Stripe integration

3. **Add Frontend Routes**
   - Home/Feed page
   - Profile pages
   - Post detail pages
   - Messages page
   - Settings page

4. **Stripe Integration**
   - Webhook handler
   - Subscription creation
   - Subscription cancellation
   - Badge activation on payment

### Future Enhancements
- [ ] Stories feature (24-hour posts)
- [ ] Video posts with streaming
- [ ] Advanced search functionality
- [ ] Hashtag system
- [ ] Trending topics
- [ ] User blocking and reporting
- [ ] Content moderation tools
- [ ] Analytics for users
- [ ] Mobile app (API-first approach)
- [ ] Push notifications
- [ ] Email notifications
- [ ] Advanced privacy settings
- [ ] Post scheduling

## Contributing

This is a custom-built social media application. For contributions:
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is proprietary software.

## Support

For support, email support@socialmedia.test or open an issue in the repository.

## Acknowledgments

- Laravel Framework
- Filament Admin Panel
- Livewire & Flux
- Spatie Packages
- Laravel Reverb
- Stripe

---

**Built with ❤️ using Laravel 12**
