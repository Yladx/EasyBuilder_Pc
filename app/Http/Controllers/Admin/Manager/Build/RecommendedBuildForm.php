<?php

namespace App\Http\Controllers\Admin\Manager\Build;

use App\Http\Controllers\Admin\Manager\AdminController;
use App\Http\Controllers\Build\BuildCompatability;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Build;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RecommendedBuildForm extends Controller
{

    public function createRecommendedBuild()
    {
        try {
            // Log::info('Accessing Recommended Build Form', [
            //     'user_id' => Auth::id(),
            //     'user_email' => Auth::user()->email ?? 'N/A',
            //     'timestamp' => now()
            // ]);

            $motherboards = DB::table('motherboards')->get(); // Ensure your 'motherboards' table has data
          
            // Log::debug('Motherboards and Power Supplies Fetched', [
            //     'motherboards_count' => $motherboards->count(),
            //     'power_supplies_count' => $powerSupplies->count()
            // ]);

            return view('admin.content.partials.recommended-build-form', compact('motherboards'));
        } catch (\Exception $e) {
            // Log::error('Error accessing Recommended Build Form', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'user_id' => Auth::id()
            // ]);

            return back()->with('error', 'Unable to load build form. Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
            // Log the incoming request details
            // Log::info('Recommended Build Creation Attempt', [
            //     'user_id' => Auth::id(),
            //     'user_email' => Auth::user()->email ?? 'N/A',
            //     'request_data' => array_filter($request->except(['_token', 'ram_id'])),
            //     'timestamp' => now()
            // ]);

            // Get the RAM IDs from the request
            $ramIds = $request->input('ram_id', []);
            if (!is_array($ramIds)) {
                $ramIds = json_decode($ramIds, true) ?? [];
            }
            $request->merge(['ram_id' => $ramIds]);

            // Validate the input data
            $validatedData = $request->validate([
                'build_name' => 'required|string|max:255',
                'motherboard_id' => 'required|exists:motherboards,id',
                'cpu_id' => 'required|exists:cpus,id',
                'gpu_id' => 'required|exists:gpus,id',
                'storage_id' => 'required|exists:storages,id',
                'ram_id' => 'required|array',
                'ram_id.*' => 'exists:rams,id',
                'power_supply_id' => 'required|exists:power_supplies,id',
                'case_id' => 'required|exists:computer_cases,id',
                'tag' => 'required|string',
                'accessories' => 'nullable|string',
                'build_note' => 'nullable|string',
                'published' => 'sometimes|boolean',
            ]);

            // Log validated data for audit purposes
            // Log::info('Validated Build Data', [
            //     'build_name' => $validatedData['build_name'],
            //     'components' => [
            //         'motherboard_id' => $validatedData['motherboard_id'],
            //         'cpu_id' => $validatedData['cpu_id'],
            //         'gpu_id' => $validatedData['gpu_id'],
            //         'storage_id' => $validatedData['storage_id'],
            //         'ram_ids' => $validatedData['ram_id'],
            //         'power_supply_id' => $validatedData['power_supply_id'],
            //         'case_id' => $validatedData['case_id']
            //     ]
            // ]);













            // Fetch the selected case to get its image
            $case = \App\Models\ComputerCase::find($request->case_id);

            if (!$case || !$case->image) {
                // Log::warning('Build Creation Failed: Invalid Case', [
                //     'case_id' => $request->case_id,
                //     'user_id' => Auth::id()
                // ]);

                return response()->json([
                    'message' => 'The selected case does not have an associated image.',
                    'type' => 'error'
                ], 422);
            }

            // Store the build in the database
            $build = Build::create(array_merge($validatedData, [
                'user_id' => Auth::id(),
                'image' => $case->image,
                'ram_id' => $validatedData['ram_id']
            ]));

            // Log successful build creation
            // Log::info('Recommended Build Created Successfully', [
            //     'build_id' => $build->id,
            //     'build_name' => $build->build_name,
            //     'user_id' => Auth::id()
            // ]);

            return response()->json([
                'message' => 'Build created successfully!',
                'build' => $build,
                'title' => 'Success!',
                'type' => 'success'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            // Log::warning('Build Creation Validation Failed', [
            //     'errors' => $e->errors(),
            //     'user_id' => Auth::id()
            // ]);

            return response()->json([
                'message' => 'Validation failed: ' . implode(' ', array_flatten($e->errors())),
                'title' => 'Validation Error!',
                'type' => 'error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Log unexpected errors
            // Log::error('Unexpected Error in Build Creation', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'user_id' => Auth::id()
            // ]);

            return response()->json([
                'message' => 'Error creating build: ' . $e->getMessage(),
                'title' => 'Error!',
                'type' => 'error'
            ], 500);
        }
    }
}
