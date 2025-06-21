<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Factory;
use App\Models\Product;

class FirebaseProductSyncController extends Controller
{
    protected $database;

    public function __construct()
    {
        $this->database = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
            ->createDatabase();
    }

    public function syncProducts()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $this->database
                ->getReference('products/' . $product->id)
                ->set([
                    'name' => $product->name,
                    'price' => $product->price,
                    'description' => $product->description,
                    'image' => asset(path: 'storage/' . $product->image),
                    'quantity' => $product->quantity,
                    'category_id' => $product->category_id,
                ]);
        }

        return response()->json(['message' => 'Products synced to Firebase successfully.']);
    }

    public function syncCategory($category)
    {
        return $this->getDatabase()
            ->getReference('categories/' . $category->id)
            ->set([
                'name' => $category->name,
                'description' => $category->description,
                'image_url' => asset('storage/' . $category->image),
            ]);
    }

}

