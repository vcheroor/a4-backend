# Laravel Blog API (Sanctum)

**GitHub URL**: https://github.com/vcheroor/blog_new_assignment2

## Summary
Laravel 11 JSON API for a simple blog. Provides public list/view endpoints and authenticated create/update/delete using Laravel Sanctum tokens. Uses MySQL and includes a seeded admin user.

## What I Did
- Set up Sanctum token auth and added middleware in `bootstrap/app.php` (stateful + bindings)
- Built `AuthController` (login, me, logout) and `PostController` (CRUD with validation)
- Defined API routes (`routes/api.php`) with public + `auth:sanctum` protected groups
- Created `posts` migration/model (`title`, `content`) with `$fillable` and relationships
- Wrote database seeder for admin user (`admin@example.com` / `password`)
- Added Postman collection for login + CRUD and verified endpoints
