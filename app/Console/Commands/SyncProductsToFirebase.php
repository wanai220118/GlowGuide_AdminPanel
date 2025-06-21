<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;
use App\Models\Product;

class SyncProductsToFirebase extends Command
{
    protected $signature = 'firebase:sync-products';
    protected $description = 'Sync products from MySQL to Firebase Realtime Database';

    public function handle()
    {
        $this->info('Starting product sync...');

        // Load Firebase credentials
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));

        // Reference to the database
        $database = $factory
            ->withDatabaseUri('https://glowguide-48f966f6-default-rtdb.asia-southeast1.firebasedatabase.app/')
            ->createDatabase();


        // Get all products
        $products = Product::all();

        foreach ($products as $product) {
            $database->getReference('products/' . $product->id)->set([
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'image' => asset('storage/' . $product->image),
                'quantity' => $product->quantity,
                'category_id' => $product->category_id,
            ]);
        }

        $this->info('Product sync complete.');
    }
}
