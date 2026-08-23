# Walkthrough - Koshi Tourism Self Sign-Up & Customer Account System

## Overview
Implemented a complete self-service customer registration, login, and traveler account portal (`/dashboard`) for tourists, while maintaining separate role-based administration for platform managers (`/admin`).

---

## 1. Customer Authentication Architecture

- **Auth Controller** ([`UserAuthController.php`](file:///C:/xampp/htdocs/koshi-tourism-laravel/app/Http/Controllers/UserAuthController.php)):
  - `showRegister()`: Renders customer sign-up page (`/register`).
  - `register()`: Validates name, unique email, phone number, and password confirmation; creates user record in MySQL; logs the user in; retroactively associates previous guest bookings with the same email.
  - `showLogin()`: Renders customer sign-in page (`/login`).
  - `login()`: Authenticates user credentials via Laravel `Auth::attempt()`.
  - `logout()`: Clears customer session and regenerates CSRF token.
  - `dashboard()`: Protected traveler account portal with active/past reservations, spent value in Nepali Rs., and trip cards.

- **Database Associations**:
  - `users` table: Added `phone` and `role` fields.
  - `bookings` table: Added `user_id` foreign key.
  - Automatic relationship between `User` (`hasMany(Booking)`) and `Booking` (`belongsTo(User)`).

---

## 2. Views & Experience

- **Customer Sign-Up** ([`auth/register.blade.php`](file:///C:/xampp/htdocs/koshi-tourism-laravel/resources/views/auth/register.blade.php)):
  - Clean, dark glassmorphic registration form with validation and password confirmation.
- **Customer Sign-In** ([`auth/login.blade.php`](file:///C:/xampp/htdocs/koshi-tourism-laravel/resources/views/auth/login.blade.php)):
  - Login screen with "Remember Me" and direct link to register.
- **Traveler Dashboard** ([`user/dashboard.blade.php`](file:///C:/xampp/htdocs/koshi-tourism-laravel/resources/views/user/dashboard.blade.php)):
  - Personalized welcome banner (*"Namaste, [Name]!"*).
  - Metrics: *My Total Bookings*, *Total Booked Value (NPR)*, *Hotel Stays*, *Guide Hires*.
  - Full reservation list with status pills and cancellation.
- **Navigation Bar Integration** ([`layouts/app.blade.php`](file:///C:/xampp/htdocs/koshi-tourism-laravel/resources/views/layouts/app.blade.php)):
  - Logged In: Displays `👤 [User Name]` badge, link to personal traveler portal, and `Logout`.
  - Logged Out: Displays `Sign In` and `Sign Up` buttons.
- **Modal Autofill** ([`public/js/app.js`](file:///C:/xampp/htdocs/koshi-tourism-laravel/public/js/app.js)):
  - When logged in, the reservation modal automatically pre-populates the customer's name and email.

---

## 3. How Users and Admins Interact

| Feature | Regular Tourist / Visitor | Platform Administrator |
| :--- | :--- | :--- |
| **Self Sign-Up** | Yes (`/register`) | Created in database / seeded |
| **Sign-In URL** | `/login` (Customer portal) | `/admin/login` (Staff portal) |
| **Dashboard Scope** | Views & manages only **their own** bookings | Views **all** bookings & province-wide revenue |
| **Status Modification** | Can cancel their own pending/confirmed booking | Can set status to `Confirmed`, `Pending`, `Completed`, `Cancelled` |
