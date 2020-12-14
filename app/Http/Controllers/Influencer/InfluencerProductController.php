<?php

namespace App\Http\Controllers\Influencer;

use App\Models\Product;
use Illuminate\Http\Request;

class InfluencerProductController
{
    public function index()
    {
        return Product::all();
    }
}
