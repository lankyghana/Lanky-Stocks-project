<?php

namespace App\Console\Commands;

use App\Models\ApiProvider;
use App\Models\Service;
use App\Lib\CurlRequest;
use Illuminate\Console\Command;

class SyncProviderPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:sync-prices {--provider= : Sync prices for specific provider ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize service prices with API providers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting price synchronization...');
        
        $providerId = $this->option('provider');
        
        if ($providerId) {
            $providers = ApiProvider::active()->where('id', $providerId)->get();
            if ($providers->isEmpty()) {
                $this->error("Provider with ID {$providerId} not found.");
                return 1;
            }
        } else {
            $providers = ApiProvider::active()->get();
        }
        
        if ($providers->isEmpty()) {
            $this->warn('No active API providers found.');
            return 0;
        }
        
        $totalUpdated = 0;
        
        foreach ($providers as $provider) {
            $this->line("Syncing prices for provider: {$provider->name}");
            
            $result = $this->syncProviderPrices($provider);
            
            if ($result['success']) {
                $this->info("✓ {$provider->name}: Updated {$result['updated']} services");
                $totalUpdated += $result['updated'];
            } else {
                $this->error("✗ {$provider->name}: {$result['message']}");
            }
        }
        
        $this->info("Price synchronization completed. Total services updated: {$totalUpdated}");
        
        return 0;
    }
    
    private function syncProviderPrices(ApiProvider $provider)
    {
        $url = $provider->api_url;
        $arr = [
            'key' => $provider->api_key,
            'action' => 'services',
        ];
        
        $response = CurlRequest::curlPostContent($url, $arr);
        $response = json_decode($response);
        
        if (@$response->error) {
            return [
                'success' => false,
                'message' => $response->error,
                'updated' => 0
            ];
        }
        
        $data = !is_null(@$response->data) ? $response->data : $response;
        $updatedCount = 0;
        
        foreach ($data as $apiService) {
            $service = Service::where('api_service_id', $apiService->service)
                            ->where('api_provider_id', $provider->id)
                            ->first();
            
            if ($service && isset($apiService->rate)) {
                $newPrice = floatval($apiService->rate);
                
                if ($service->original_price != $newPrice) {
                    // Calculate new price_per_k based on the same multiplier
                    $multiplier = $service->original_price > 0 ? ($service->price_per_k / $service->original_price) : 1;
                    
                    $service->original_price = $newPrice;
                    $service->price_per_k = $newPrice * $multiplier;
                    $service->save();
                    
                    $updatedCount++;
                }
            }
        }
        
        return [
            'success' => true,
            'message' => "Successfully synced prices",
            'updated' => $updatedCount
        ];
    }
}
