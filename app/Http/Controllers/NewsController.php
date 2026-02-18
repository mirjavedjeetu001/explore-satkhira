<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::active();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_bn', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $news = $query->latest()->paginate(12);

        $featuredNews = News::active()->featured()->latest()->take(3)->get();

        return view('frontend.news.index', compact('news', 'featuredNews'));
    }

    public function show(News $news)
    {
        if (!$news->is_active) {
            abort(404);
        }

        $news->incrementViews();

        $relatedNews = News::active()
            ->where('type', $news->type)
            ->where('id', '!=', $news->id)
            ->latest()
            ->take(4)
            ->get();

        return view('frontend.news.show', compact('news', 'relatedNews'));
    }
}
