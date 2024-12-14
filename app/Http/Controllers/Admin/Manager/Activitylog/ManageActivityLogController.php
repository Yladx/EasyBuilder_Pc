<?php

namespace App\Http\Controllers\Admin\Manager\Activitylog;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManageActivityLogController extends Controller
{
    public function getActivityLogs()
    {
        $initialLogs = ActivityLog::with('user')
            ->orderBy('activity_timestamp', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'activity_timestamp' => $log->activity_timestamp->format('Y-m-d H:i:s'),
                    'user' => $log->user ? $log->user->name : 'N/A',
                    'action' => $log->action,
                    'type' => $log->type,
                    'activity' => $log->activity,
                    'activity_details' => $log->activity_details
                ];
            });

        return view('admin.content.manage-activitylogs', compact('initialLogs'));
    }

    public function getActivityLogsData(Request $request): JsonResponse
    {
        try {
            $query = ActivityLog::with('user');

            \Log::info('Filter Parameters:', [
                'action' => $request->input('action'),
                'type' => $request->input('type'),
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to')
            ]);

            if ($request->input('action')) {
                $query->where('action', $request->input('action'));
            }

            if ($request->input('type')) {
                $query->where('type', $request->input('type'));
            }

            if ($request->input('date_from')) {
                $query->whereDate('activity_timestamp', '>=', $request->input('date_from'));
            }

            if ($request->input('date_to')) {
                $query->whereDate('activity_timestamp', '<=', $request->input('date_to'));
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', function ($row) {
                    return '';
                })
                ->editColumn('activity_timestamp', function($row) {
                    return $row->activity_timestamp ? $row->activity_timestamp->format('Y-m-d H:i:s') : '';
                })
                ->editColumn('user_id', function($row) {
                    return $row->user ? $row->user->name : 'N/A';
                })
                ->toJson();

        } catch (\Exception $e) {
            \Log::error('Error in getActivityLogsData: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
