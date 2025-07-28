# Admin User Role Manager

This Laravel module provides a simple CRUD (Create, Read, Update, Delete) interface for managing basic website configuration user roles. It enables administrators to easily control and update core user roles used throughout the application.

---

## Features

- User Role Management: Create, edit, and delete roles
- Search and filter support

---

## Requirements


- PHP >=8.2
- Laravel Framework >= 12.x

---

## Installation

### 1. Add Git Repository to `composer.json`

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-user-roles.git"
    }
]
```

### 2. Require the package via Composer
    ```bash
    composer require admin/user_roles:@dev
    ```

### 3. **Publish assets:**
    ```bash
    php artisan user_roles:publish --force
    ```
---

## Usage

1. **Create**: Add new website user roles such as name and status.
2. **Read**: View all current user roles in a user-friendly admin panel.
3. **Update**: Edit existing website configuration user roles.
4. **Delete**: Remove user roles that are no longer required.

### Admin Panel Routes

| Method | Endpoint              | Description                          |
|--------|-----------------------|--------------------------------------|
| GET    | `/user_roles`         | List all website user roles          |
| POST   | `/user_roles`         | Create new website user role         |
| GET    | `/user_roles/{id}`    | Get details of a specific user role  |
| PUT    | `/user_roles/{id}`    | Update a website user role           |
| DELETE | `/user_roles/{id}`    | Delete a website user role           |

---

## Protecting Admin Routes

Protect your admin user roles routes using the provided middleware:

```php
Route::middleware(['web','admin.auth'])->group(function () {
    // Admin user roles routes here
});
```
---

## Database Table

- `user_roles` - Stores setitngs information
---

## Configuration

Edit the `config/user_role.php` file to customize module user roles.

---

## License

This package is open-sourced software licensed under the MIT license.

 