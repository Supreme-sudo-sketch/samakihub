<?php

namespace App\Console\Commands;

use App\Services\PesaPalService;
use Illuminate\Console\Command;

class TestPesapalToken extends Command
{
    protected $signature = 'pesapal:test-token';
    protected $description = 'Test PesaPal token authentication';

    public function handle()
    {
        try {
            $this->info('Testing PesaPal API authentication...');
            $this->info('Environment: ' . config('pesapal.environment'));
            
            $service = new PesaPalService();
            $token = $service->getAccessToken();
            
            if ($token) {
                $this->info('✓ SUCCESS: PesaPal authentication working!');
                $this->info('Token (first 40 chars): ' . substr($token, 0, 40) . '...');
                return 0;
            }
            
            $this->error('✗ ERROR: No token received');
            return 1;
        } catch (\Exception $e) {
            $this->error('✗ ERROR: ' . $e->getMessage());
            return 1;
        }
    }
}

