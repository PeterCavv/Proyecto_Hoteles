<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use AuthorizesRequests;
    public function reviewsPdf(User $user, Request $request)
    {

        $ids = $request->query('ids');
        $reviewIds = $ids ? explode(',', $ids) : [];

        $reviewsQuery = $user->reviews()->with('hotel');

        if (!empty($reviewIds)) {
            $reviewsQuery->whereIn('id', $reviewIds);
        }

        $reviews = $reviewsQuery->get();

        $pdf = Pdf::loadView('pdfs.reviews', [
            'appName' => 'HotelFinder',
            'user' => $user,
            'reviews' => $reviews,
        ]);

        return $pdf->stream("hotel-finder-{$user->id}-reviews.pdf");
    }
}
