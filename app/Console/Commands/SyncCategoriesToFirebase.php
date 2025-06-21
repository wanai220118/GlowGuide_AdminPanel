<?php

namespace App\Console\Commands;

use Kreait\Firebase\Factory;
use App\Models\Category;
use Illuminate\Console\Command;

class SyncCategoriesToFirebase extends Command
{
    protected $signature = 'firebase:sync-categories';
    protected $description = 'Sync categories from MySQL to Firebase Realtime Database';

    public function handle()
    {
        $this->info('Starting category sync...');

        // Load Firebase credentials
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));

        // Reference to the database
        $database = $factory
            ->withDatabaseUri('https://glowguide-48f966f6-default-rtdb.asia-southeast1.firebasedatabase.app/')
            ->createDatabase();


        // Get all categories
        $categories = Category::all();

        foreach ($categories as $category) {
            $database->getReference('categories/' . $category->id)->set([
                'name' => $category->name,
                'description' => $category->description,
                'image_url' => asset('storage/' . $category->image),
            ]);
        }

        $this->info('Category sync complete.');
    }
}
