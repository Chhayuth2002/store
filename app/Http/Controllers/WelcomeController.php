<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->where('parent_id', null)->orderBy('name', 'asc')->get(['name', 'slug']);
        return Inertia::render('Welcome', [
            'categories' => $categories
        ]);
    }
}
