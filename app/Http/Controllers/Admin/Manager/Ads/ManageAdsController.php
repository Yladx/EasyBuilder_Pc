<?php

namespace App\Http\Controllers\Admin\Manager\Ads;

use App\Http\Requests\Ads\StoreAdRequest;
use App\Http\Requests\Ads\ToggleAdRequest;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class ManageAdsController extends Controller
{
    public function indexAds()
    {
        try {
            // Log::info('Ads Management Page Access', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'timestamp' => now()
            // ]);

            // Fetch all advertisements
            $advertisements = Advertisement::all();

            // Fetch ad statistics
            $totalAds = Advertisement::count();
            $publishedAds = Advertisement::where('advertise', true)->count();
            $unpublishedAds = $totalAds - $publishedAds;

            // Log::debug('Advertisements Retrieved', [
            //     'total_ads' => count($advertisements),
            //     'search_applied' => false,
            //     'status_filter_applied' => false
            // ]);

            if (request()->ajax()) {
                return response()->json([
                    'adStats' => [
                        'total' => $totalAds,
                        'published' => $publishedAds,
                        'unpublished' => $unpublishedAds,
                    ]
                ]);
            }
            // Pass data to the view
            return view('admin.content.manage-ads', [
                'advertisements' => $advertisements,
                'adStats' => [
                    'total' => $totalAds,
                    'published' => $publishedAds,
                    'unpublished' => $unpublishedAds,
                ],
            ]);
        } catch (\Exception $e) {
            // Log::error('Error Accessing Ads Management Page', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id()
            // ]);

            return back()->with('error', 'Unable to load ads management page.');
        }
    }

    public function getAddForm()
    {
        try {
            // Log::info('Ad Creation Form Access', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'timestamp' => now()
            // ]);

            return view('admin.content.partials.add-ads-form');
        } catch (\Exception $e) {
            // Log::error('Error Accessing Ad Creation Form', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id()
            // ]);

            return back()->with('error', 'Unable to load ad creation form.');
        }
    }

    public function storeAds(StoreAdRequest $request)
    {
        try {
            // Log::info('Ad Creation Attempt', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'ad_title' => $request->input('title'),
            //     'timestamp' => now()
            // ]);

            // Handle file upload if provided
            $srcPath = null;
            if ($request->hasFile('src')) {
                $folderPath = 'ads'; // Directory where ads files will be stored

                // Generate a unique filename
                $extension = $request->file('src')->getClientOriginalExtension();
                $fileName = uniqid('ad_') . '.' . $extension;

                // Store the file
                $srcPath = $request->file('src')->storeAs($folderPath, $fileName, 'public'); // Store in 'public/ads'
            }

            // Debugging log to check file path
            // Log::info('File Path: ' . $srcPath);

            // Create the advertisement record
            $advertisement = Advertisement::create(array_merge($request->validated(), [
                'src' => $srcPath, // Save the file path in the 'src' field
                'advertise' => $request->has('advertise') ? (bool) $request->advertise : false,
            ]));

            // Log::info('Ad Created Successfully', [
            //     'ad_id' => $advertisement->id,
            //     'ad_title' => $advertisement->title,
            //     'ad_status' => $advertisement->advertise,
            //     'admin_id' => Auth::id()
            // ]);

            // Redirect back with success message
            return redirect()->back()->with('success', 'Advertisement created successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            // Log::error('Ad Creation Error: ' . $e->getMessage(), [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id()
            // ]);

            // Redirect back with error message
            return redirect()->back()->withErrors(['error' => 'An error occurred while creating the advertisement.']);
        }
    }

    public function destroyAds($id)
    {
        try {
            // Log::info('Ad Deletion Attempt', [
            //     'admin_id' => Auth::id(),
            //     'ad_id' => $id,
            //     'timestamp' => now()
            // ]);

            $ad = Advertisement::findOrFail($id);

            // Get the file path before deleting the record
            $filePath = $ad->src;

            // Delete the advertisement record
            $ad->delete();

            // Delete the file if it exists
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                // Log::info('Deleted file: ' . $filePath);
            }

            // Log::info('Ad Deleted Successfully', [
            //     'deleted_ad_id' => $id,
            //     'admin_id' => Auth::id()
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Advertisement deleted successfully.',
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            // Log::error('Ad Deletion Error: ' . $e->getMessage(), [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'ad_id' => $id
            // ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the advertisement.',
            ], 500);
        }
    }

    public function toggleAdvertise(ToggleAdRequest $request, int $id): JsonResponse
    {
        try {
            // Log::info('Ad Status Toggle Attempt', [
            //     'admin_id' => Auth::id(),
            //     'ad_id' => $id,
            //     'timestamp' => now()
            // ]);

            $advertisement = Advertisement::findOrFail($id);
            $advertisement->advertise = $request->advertise;
            $advertisement->save();

            $message = $advertisement->advertise
                ? 'Advertisement has been set to advertised.'
                : 'Advertisement has been set to not advertised.';

            // Log::info('Ad Status Toggled Successfully', [
            //     'ad_id' => $id,
            //     'new_status' => $advertisement->advertise,
            //     'admin_id' => Auth::id()
            // ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_status' => $advertisement->advertise,
            ]);
        } catch (\Exception $e) {
            // Log::error('Ad Status Toggle Error: ' . $e->getMessage(), [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'ad_id' => $id
            // ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the advertisement status.',
            ], 500);
        }
    }
}
