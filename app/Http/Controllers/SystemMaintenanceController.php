<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SystemMaintenanceController extends Controller
{
    /**
     * Clear application cache.
     */
    public function optimize()
    {
        Log::info('Maintenance: Starting optimize:clear');
        try {
            Artisan::call('optimize:clear');
            Log::info('Maintenance: optimize:clear completed');
            return response()->json([
                'success' => 'System cache cleared successfully.',
                'output' => Artisan::output()
            ]);
        } catch (\Exception $e) {
            Log::error('Maintenance: optimize:clear failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Run database migrations.
     */
    public function migrate()
    {
        Log::info('Maintenance: Starting migrate --force');
        try {
            Artisan::call('migrate', ['--force' => true]);
            Log::info('Maintenance: migrate completed');
            return response()->json([
                'success' => 'Database migrated successfully.',
                'output' => Artisan::output()
            ]);
        } catch (\Exception $e) {
            Log::error('Maintenance: migrate failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create storage link.
     */
    public function storageLink()
    {
        Log::info('Maintenance: Starting storage:link');
        try {
            Artisan::call('storage:link');
            Log::info('Maintenance: storage:link completed');
            return response()->json([
                'success' => 'Storage link created successfully.',
                'output' => Artisan::output()
            ]);
        } catch (\Exception $e) {
            Log::error('Maintenance: storage:link failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Run composer update.
     */
    public function composerUpdate()
    {
        Log::info('Maintenance: Starting composer update');
        
        if (!function_exists('shell_exec')) {
            Log::error('Maintenance: shell_exec is disabled on this server.');
            return response()->json(['error' => 'The "shell_exec" function is disabled on this server. This operation is not possible on shared hosting like Hostinger.'], 403);
        }

        set_time_limit(600); // Composer update can take time
        try {
            // Find composer path (could be composer or composer.phar)
            $composer = 'composer'; 
            
            // On Windows, sometimes we need to use 'php composer.phar'
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $check = \shell_exec('where composer');
                if (!$check) {
                    $composer = 'php composer.phar';
                }
            }

            $output = \shell_exec($composer . ' update --no-interaction 2>&1');
            Log::info('Maintenance: composer update completed');
            
            return response()->json([
                'success' => 'Composer update completed.',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            Log::error('Maintenance: composer update failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
