<?php

namespace App\Console\Commands;

use App\Services\PesaPalService;
use Illuminate\Console\Command;

class PesapalRegisterIpn extends Command
{
    protected $signature = 'pesapal:register-ipn';
    protected $description = 'Register IPN with PesaPal (run once and save PESAPAL_IPN_ID to .env)';

    public function handle()
    {
        try {
            $this->info('Registering IPN with PesaPal...');
            
            $service = new PesaPalService();
            $ipnId = $service->registerIPN();
            
            $this->info('✓ IPN registered successfully!');
            $this->warn("\nImportant: Add this to your .env file:");
            $this->line("PESAPAL_IPN_ID={$ipnId}");
            $this->line("\nThen run: php artisan config:cache");
            
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ IPN registration failed: ' . $e->getMessage());
            return 1;
        }
    }
}
