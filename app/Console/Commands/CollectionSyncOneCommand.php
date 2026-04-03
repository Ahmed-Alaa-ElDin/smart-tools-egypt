<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Collection;
use App\Services\Front\meta\MetaCatalogService;

class CollectionSyncOneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meta:collection-sync-one {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync a single collection (bundle) to Facebook Catalog by its ID';

    /**
     * Execute the console command.
     */
    public function handle(MetaCatalogService $service)
    {
        $id = $this->argument('id');
        $collection = Collection::find($id);

        if (!$collection) {
            $this->error("Collection with ID {$id} not found.");
            return;
        }

        $this->info("Syncing Collection: {$collection->name} (ID: {$collection->id})...");

        if ($service->syncCollection($collection)) {
            $this->info("Collection synced successfully.");
        } else {
            $this->error("Failed to sync collection. Check logs for details.");
        }
    }
}
