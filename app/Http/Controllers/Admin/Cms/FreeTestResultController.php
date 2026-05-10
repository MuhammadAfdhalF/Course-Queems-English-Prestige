<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\FreeTest;
use App\Models\FreeTestResult;
use Illuminate\Http\Request;

class FreeTestResultController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $selectedFreeTestId = $request->query('free_test_id');

        $freeTests = FreeTest::query()
            ->orderBy('title')
            ->get();

        $results = FreeTestResult::query()
            ->with('freeTest.categoryRelation')
            ->when(filled($search), function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery
                        ->where('participant_name', 'like', '%' . $search . '%')
                        ->orWhere('participant_email', 'like', '%' . $search . '%')
                        ->orWhere('participant_whatsapp', 'like', '%' . $search . '%');
                });
            })
            ->when(filled($selectedFreeTestId), function ($query) use ($selectedFreeTestId) {
                $query->where('free_test_id', $selectedFreeTestId);
            })
            ->latest('submitted_at')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.cms.free-test-results.index', compact(
            'results',
            'freeTests',
            'search',
            'selectedFreeTestId'
        ));
    }
}
