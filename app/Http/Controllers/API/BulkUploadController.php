<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BulkUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BulkUploadController extends Controller
{
    public function __construct(
        protected BulkUploadService $uploadService
    ) {}

    /**
     * Upload farmers via CSV
     */
    public function uploadFarmers(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120', // 5MB max
        ]);

        $rows = $this->uploadService->parseCsv($request->file('file'));

        if (empty($rows)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid rows found in the CSV file',
            ], 422);
        }

        $results = $this->uploadService->uploadFarmers($rows, $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => sprintf(
                'Processed %d rows: %d successful, %d failed',
                $results['total'],
                $results['success'],
                $results['failed']
            ),
            'data' => $results,
        ]);
    }

    /**
     * Upload retailers via CSV
     */
    public function uploadRetailers(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $rows = $this->uploadService->parseCsv($request->file('file'));

        if (empty($rows)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid rows found in the CSV file',
            ], 422);
        }

        $results = $this->uploadService->uploadRetailers($rows, $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => sprintf(
                'Processed %d rows: %d successful, %d failed',
                $results['total'],
                $results['success'],
                $results['failed']
            ),
            'data' => $results,
        ]);
    }

    /**
     * Upload SDTV hierarchy via CSV (Admin only)
     */
    public function uploadSdtv(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $rows = $this->uploadService->parseCsv($request->file('file'));

        if (empty($rows)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid rows found in the CSV file',
            ], 422);
        }

        $results = $this->uploadService->uploadSdtvNodes($rows);

        return response()->json([
            'success' => true,
            'message' => sprintf(
                'Processed %d rows: %d successful, %d failed',
                $results['total'],
                $results['success'],
                $results['failed']
            ),
            'data' => $results,
        ]);
    }

    /**
     * Upload ZRTH hierarchy via CSV (Admin only)
     */
    public function uploadZrth(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $rows = $this->uploadService->parseCsv($request->file('file'));

        if (empty($rows)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid rows found in the CSV file',
            ], 422);
        }

        $results = $this->uploadService->uploadZrthNodes($rows);

        return response()->json([
            'success' => true,
            'message' => sprintf(
                'Processed %d rows: %d successful, %d failed',
                $results['total'],
                $results['success'],
                $results['failed']
            ),
            'data' => $results,
        ]);
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate(string $type): Response
    {
        $validTypes = ['farmers', 'retailers', 'sdtv', 'zrth'];

        if (!in_array($type, $validTypes)) {
            abort(404, 'Template not found');
        }

        $content = $this->uploadService->getTemplate($type);
        $filename = "{$type}_template.csv";

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
