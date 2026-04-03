<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Collection;
use App\Services\Front\meta\MetaCatalogService;

class CollectionSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meta:collection-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all published collections (bundles) to Facebook Catalog';

    /**
     * Execute the console command.
     */
    public function handle(MetaCatalogService $service)
    {
        // Fetch only published collections that are not under review
        $collections = Collection::where('publish', 1)
            ->where('under_reviewing', 0)
            ->get();
            
        $this->info("Found {$collections->count()} collections to sync.");

        if ($collections->isEmpty()) {
            return;
        }

        // Batch in groups of 50
        $collections->chunk(50)->each(function ($chunk) use ($service) {
            if ($service->syncItems($chunk)) {
                $this->comment("Synced a batch of {$chunk->count()} collections.");
            } else {
                $this->error("Failed to sync a batch of collections.");
            }
        });

        $this->info('Collections sync completed.');
    }
}
