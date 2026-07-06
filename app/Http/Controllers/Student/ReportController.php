<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Submit a report for inappropriate content
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'reportable_type' => 'required|string',
            'reportable_id' => 'required|integer',
            'reason' => 'required|in:spam,harassment,inappropriate_content,misinformation,copyright,other',
            'description' => 'nullable|string|max:1000',
        ]);

        // Check if user already reported this content
        $existingReport = Report::where('reporter_id', auth()->id())
            ->where('reportable_type', $request->reportable_type)
            ->where('reportable_id', $request->reportable_id)
            ->where('status', '!=', 'resolved')
            ->first();

        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reported this content',
            ], 422);
        }

        $report = Report::submit(
            reporterId: auth()->id(),
            reportableType: $request->reportable_type,
            reportableId: $request->reportable_id,
            reason: $request->reason,
            description: $request->description
        );

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully. Thank you for helping keep our community safe.',
            'data' => [
                'report_id' => $report->id,
            ],
        ]);
    }

    /**
     * Get user's submitted reports
     */
    public function myReports(): JsonResponse
    {
        $reports = Report::where('reporter_id', auth()->id())
            ->with('reportable')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reports,
        ]);
    }
}
