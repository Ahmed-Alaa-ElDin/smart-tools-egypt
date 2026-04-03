<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CatalogSyncOneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meta:catalog-sync-one {id}';
    protected $description = 'Sync a single product to Facebook Catalog by ID';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\Front\meta\MetaCatalogService $service)
    {
        $id = $this->argument('id');
        $product = \App\Models\Product::find($id);

        if (!$product) {
            $this->error("Product with ID {$id} not found.");
            return;
        }

        $this->info("Syncing Product: {$product->name} (ID: {$id})...");

        if ($service->syncProduct($product)) {
            $this->info('Product synced successfully.');
        } else {
            $this->error('Failed to sync product. Check logs for details.');
        }
    }
}
