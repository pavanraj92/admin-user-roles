<?php

namespace admin\user_roles;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserRoleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package  
        $this->loadViewsFrom([
            base_path('Modules/UserRoles/resources/views'), // Published module views first
            resource_path('views/admin/user_role'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'user_role');

        $this->mergeConfigFrom(__DIR__.'/../config/user_role.php', 'user_role.constants');
        
        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/UserRoles/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/UserRoles/resources/views'), 'user_roles-module');
        }

           // Also merge config from published module if it exists
        if (file_exists(base_path('Modules/UserRoles/config/user_role.php'))) {
            $this->mergeConfigFrom(base_path('Modules/UserRoles/config/user_role.php'), 'user_role.constants');
        }

        // Only publish automatically during package installation, not on every request
        // Use 'php artisan user_roles:publish' command for manual publishing
        // $this->publishWithNamespaceTransformation();
        
        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('Modules/UserRoles/resources/views/'),
        ], 'user_role');
       
        $this->registerAdminRoutes();

    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $admin = DB::table('admins')
            ->orderBy('created_at', 'asc')
            ->first();
            
        $slug = $admin->website_slug ?? 'admin';

        $routeFile = base_path('Modules/UserRoles/routes/web.php');
        if (!file_exists($routeFile)) {
            $routeFile = __DIR__ . '/routes/web.php'; // fallback to package route
        }

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group($routeFile);
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\user_roles\Console\Commands\PublishUserRolesModuleCommand::class,
                \admin\user_roles\Console\Commands\CheckModuleStatusCommand::class,
                \admin\user_roles\Console\Commands\DebugUserRolesCommand::class,
                \admin\user_roles\Console\Commands\TestViewResolutionCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../src/Controllers/UserRoleManagerController.php' => base_path('Modules/UserRoles/app/Http/Controllers/Admin/UserRoleManagerController.php'),
            
            // Requests
            __DIR__ . '/../src/Requests/UserRoleCreateRequest.php' => base_path('Modules/UserRoles/app/Http/Requests/UserRoleCreateRequest.php'),
            __DIR__ . '/../src/Requests/UserRoleUpdateRequest.php' => base_path('Modules/UserRoles/app/Http/Requests/UserRoleUpdateRequest.php'),
            
            // Routes
            __DIR__ . '/routes/web.php' => base_path('Modules/UserRoles/routes/web.php'),

        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));
                
                // Read the source file
                $content = File::get($source);
                
                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);
                
                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    /**
     * Transform namespaces in PHP files
     */
    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\user_roles\\Controllers;' => 'namespace Modules\\UserRoles\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\user_roles\\Requests;' => 'namespace Modules\\UserRoles\\app\\Http\\Requests;',
            
            // Use statements transformations
            'use admin\\user_roles\\Controllers\\' => 'use Modules\\UserRoles\\app\\Http\\Controllers\\Admin\\',
            'use admin\\user_roles\\Requests\\' => 'use Modules\\UserRoles\\app\\Http\\Requests\\',
            
            // Class references in routes
            'admin\\user_roles\\Controllers\\UserRoleManagerController' => 'Modules\\UserRoles\\app\\Http\\Controllers\\Admin\\UserRoleManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = $this->transformControllerNamespaces($content);
        } elseif (str_contains($sourceFile, 'Requests')) {
            $content = $this->transformRequestNamespaces($content);
        } elseif (str_contains($sourceFile, 'routes')) {
            $content = $this->transformRouteNamespaces($content);
        }

        return $content;
    }

    /**
     * Transform controller-specific namespaces
     */
    protected function transformControllerNamespaces($content)
    {
        // Update use statements for models and requests
        $content = str_replace(
            'use admin\\user_roles\\Requests\\UserRoleCreateRequest;',
            'use Modules\\UserRoles\\app\\Http\\Requests\\UserRoleCreateRequest;',
            $content
        );
        
        $content = str_replace(
            'use admin\\user_roles\\Requests\\UserRoleUpdateRequest;',
            'use Modules\\UserRoles\\app\\Http\\Requests\\UserRoleUpdateRequest;',
            $content
        );

        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        // Any model-specific transformations
        return $content;
    }

       /**
     * Transform mail-specific namespaces
     */
    protected function transformMailNamespaces($content)
    {
        // Any mail-specific transformations
        return $content;
    }


    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        // Any request-specific transformations
        return $content;
    }

    protected function transformSeederNamespaces($content)
    {
        // Add any seeder-specific transformations here if needed
        return $content;
    }

    /**
     * Transform route-specific namespaces
     */
    protected function transformRouteNamespaces($content)
    {
        // Update controller references in routes
        $content = str_replace(
            'admin\\user_roles\\Controllers\\UserRoleManagerController',
            'Modules\\UserRoles\\app\\Http\\Controllers\\Admin\\UserRoleManagerController',
            $content
        );

        return $content;
    }
}

