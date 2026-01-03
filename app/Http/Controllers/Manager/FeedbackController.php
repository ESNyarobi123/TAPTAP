<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with('order')->latest()->paginate(10);
        $avgRating = Feedback::avg('rating') ?? 0;
        $totalReviews = Feedback::count();
        
        $ratingBreakdown = Feedback::select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->all();

        return view('manager.feedback.index', compact('feedbacks', 'avgRating', 'totalReviews', 'ratingBreakdown'));
    }
}
