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
        $restaurantId = auth()->user()->restaurant_id;
        $feedbacks = Feedback::with('order')->where('restaurant_id', $restaurantId)->latest()->paginate(10);
        $avgRating = Feedback::where('restaurant_id', $restaurantId)->avg('rating') ?? 0;
        $totalReviews = Feedback::where('restaurant_id', $restaurantId)->count();
        
        $ratingBreakdown = Feedback::where('restaurant_id', $restaurantId)
            ->select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->all();

        return view('manager.feedback.index', compact('feedbacks', 'avgRating', 'totalReviews', 'ratingBreakdown'));
    }
}
