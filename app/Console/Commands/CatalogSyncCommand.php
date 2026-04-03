<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CatalogSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meta:catalog-sync';
    protected $description = 'Sync all published products to Facebook Catalog';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\Front\meta\MetaCatalogService $service)
    {
        $products = \App\Models\Product::where('publish', 1)
            ->where('under_reviewing', 0)
            ->get();
            
        $this->info("Found {$products->count()} products to sync.");

        // Batch in groups of 50
        $products->chunk(50)->each(function ($chunk) use ($service) {
            if ($service->syncItems($chunk)) {
                $this->comment("Synced a batch of {$chunk->count()} products.");
            } else {
                $this->error("Failed to sync a batch.");
            }
        });

        $this->info('Catalog sync completed.');
    }
}
