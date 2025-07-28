<?php

namespace admin\user_roles\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishUserRolesModuleCommand extends Command
{
    protected $signature = 'user_roles:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish UserRoles module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing UserRoles module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/UserRoles');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'user_role',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('UserRoles module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__)); // Go up to packages/admin/user_roles/src
        
        $filesWithNamespaces = [
            // Controllers
            $basePath . '/Controllers/UserRoleManagerController.php' => base_path('Modules/UserRoles/app/Http/Controllers/Admin/UserRoleManagerController.php'),
            
            // Models
            $basePath . '/Models/UserRole.php' => base_path('Modules/UserRoles/app/Models/UserRole.php'),
            
            // Requests
            $basePath . '/Requests/UserRoleCreateRequest.php' => base_path('Modules/UserRoles/app/Http/Requests/UserRoleCreateRequest.php'),
            $basePath . '/Requests/UserRoleUpdateRequest.php' => base_path('Modules/UserRoles/app/Http/Requests/UserRoleUpdateRequest.php'),
            
            // Routes
            $basePath . '/routes/web.php' => base_path('Modules/UserRoles/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }

    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\user_roles\\Controllers;' => 'namespace Modules\\UserRoles\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\user_roles\\Models;' => 'namespace Modules\\UserRoles\\app\\Models;',
            'namespace admin\\user_roles\\Requests;' => 'namespace Modules\\UserRoles\\app\\Http\\Requests;',
            
            // Use statements transformations
            'use admin\\user_roles\\Controllers\\' => 'use Modules\\UserRoles\\app\\Http\\Controllers\\Admin\\',
            'use admin\\user_roles\\Models\\' => 'use Modules\\UserRoles\\app\\Models\\',
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
            $content = str_replace('use admin\\user_roles\\Models\\UserRole;', 'use Modules\\UserRoles\\app\\Models\\UserRole;', $content);
            $content = str_replace('use admin\\user_roles\\Requests\\UserRoleCreateRequest;', 'use Modules\\UserRoles\\app\\Http\\Requests\\UserRoleCreateRequest;', $content);
            $content = str_replace('use admin\\user_roles\\Requests\\UserRoleUpdateRequest;', 'use Modules\\UserRoles\\app\\Http\\Requests\\UserRoleUpdateRequest;', $content);
        }

        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\UserRoles\\'])) {
            $composer['autoload']['psr-4']['Modules\\UserRoles\\'] = 'Modules/UserRoles/app/';
            
            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}
