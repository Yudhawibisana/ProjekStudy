<?php

namespace App\Http\Controllers\Back;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Postingan;

class DashboardController extends Controller
{
    public function index()
    {
        return view('back.dashboard.index', [
            'total_postingans' => Postingan::count(),
            'total_categories' => Category::count(),
            'latest_postingan' => Postingan::with('Category')->whereStatus(1)->latest()->take(5)->get(),
            'popular_postingan' => Postingan::with('Category')->whereStatus(1)->orderBy('views', 'desc')->take(5)->get()
        ]);
    }
}
