<?php

namespace App\Http\Controllers\Admin\Manager\PartsComponent;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ComponentsExport;
use App\Models\Component;

class ManageComponentController extends Controller
{

    public function create()
    {
        try {
            // Log::info('Component Management Page Access', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'timestamp' => now()
            // ]);

            return view('admin.content.manages-component');
        } catch (\Exception $e) {
            // Log::error('Error Accessing Component Management Page', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id()
            // ]);

            return back()->with('error', 'Unable to load component management page.');
        }
    }

    public function countcomponents(): array
    {
        try {
            $componentCounts = [
                'cpuCount' => DB::table('cpus')->count(),
                'gpuCount' => DB::table('gpus')->count(),
                'motherboardCount' => DB::table('motherboards')->count(),
                'ramCount' => DB::table('rams')->count(),
                'storageCount' => DB::table('storages')->count(),
                'powerSupplyCount' => DB::table('power_supplies')->count(),
                'caseCount' => DB::table('computer_cases')->count(),
            ];

            // Log::debug('Component Counts Retrieved', [
            //     'admin_id' => Auth::id(),
            //     'component_counts' => $componentCounts
            // ]);

            return $componentCounts;
        } catch (\Exception $e) {
            // Log::error('Error Counting Components', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id()
            // ]);

            return [];
        }
    }

    public function fetchcomponentcounts(): JsonResponse
    {
        try {
            // Fetch the component counts
            $componentCounts = $this->countcomponents();

            // Log::info('Component Counts Fetched via API', [
            //     'admin_id' => Auth::id(),
            //     'component_counts' => $componentCounts
            // ]);

            return response()->json($componentCounts, 200);
        } catch (\Exception $e) {
            // Log::error('Error Fetching Component Counts via API', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id()
            // ]);

            return response()->json([
                'error' => 'Unable to fetch component counts.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function addComponent(Request $request)
    {
        try {
            // Log::info('Component Addition Attempt', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'component_type' => $request->input('componentType'),
            //     'timestamp' => now()
            // ]);

            // Determine the table name based on the component type
            $tableMap = [
                'cpus' => 'cpus',
                'gpus' => 'gpus',
                'motherboards' => 'motherboards',
                'rams' => 'rams',
                'storages' => 'storages',
                'power_supplies' => 'power_supplies',
                'cases' => 'cases',
            ];

            $componentType = $request->input('componentType');
            $tableName = $tableMap[$componentType] ?? null;

            if (!$tableName) {
                return response()->json(['error' => 'Invalid component type'], 400);
            }

            // Validate and insert data
            $columns = Schema::getColumnListing($tableName);
            $rules = [];
            $data = $request->all(); // Get all request data

            foreach ($columns as $column) {
                $isNullable = Schema::getConnection()->getDoctrineSchemaManager()->listTableDetails($tableName)->getColumn($column)->getNotnull() === false;

                if ($column === 'image') {
                    $rules[$column] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
                } else {
                    $rules[$column] = $isNullable ? 'nullable|string|max:255' : 'required|string|max:255';
                }
            }

            $validatedData = $request->validate($rules);

            // Handle file upload if there is an image
            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image')->store('images', 'public');
            }

            // Insert the validated data into the corresponding table
            DB::table($tableName)->insert($validatedData);

            // Log::info('Component Added Successfully', [
            //     'component_type' => $componentType,
            //     'admin_id' => Auth::id()
            // ]);

            return response()->json(['message' => 'Component added successfully!'], 201);
        } catch (\Exception $e) {
            // Log::error('Error Adding Component', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'component_type' => $request->input('componentType')
            // ]);

            return response()->json(['error' => 'Failed to add component: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request, $componentType)
    {
        try {
            // Log::info('Component Store Attempt', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'component_type' => $componentType,
            //     'timestamp' => now()
            // ]);

            // Define valid component types
            $validTables = ['cpus', 'gpus', 'motherboards', 'rams', 'storages', 'power_supplies', 'computer_cases'];

            // Check if the component type is valid
            if (!in_array($componentType, $validTables)) {
                return response()->json(['error' => 'Invalid component type.'], 400);
            }

            // Automatically get the fields for the specified table, excluding `id`, `created_at`, and `updated_at`
            $fields = array_diff(Schema::getColumnListing($componentType), ['id', 'created_at', 'updated_at']);

            // Set validation rules for each field (excluding `id`, `created_at`, and `updated_at`)
            $validationRules = array_fill_keys($fields, 'required');

            // Modify image validation to be nullable
            $validationRules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';

            // Validate the request with the specified rules
            $request->validate($validationRules);

            // Retrieve all input data and filter it to only include valid fields
            $inputData = $request->only($fields);

            // Add created_at and updated_at timestamps manually
            $inputData['created_at'] = now();
            $inputData['updated_at'] = now();

            // Create a new component in the database and get the inserted ID
            $componentId = DB::table($componentType)->insertGetId($inputData);

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // Set the folder path based on the component type
                $folderPath = 'component_images/' . $componentType;

                // Generate the image name in the format: componentType-componentId.extension
                $imageExtension = $image->getClientOriginalExtension(); // Get the image extension
                $imageName = "{$componentType}-{$componentId}.{$imageExtension}"; // Create the new name

                // Store the image in the specified folder path within the 'public' storage
                $storedPath = $image->storeAs($folderPath, $imageName, 'public');

                // Save the relative path in the database (assuming there's an `image` field in the table)
                DB::table($componentType)->where('id', $componentId)->update(['image' => $storedPath]);
            }

            // Log::info('Component Stored Successfully', [
            //     'component_id' => $componentId,
            //     'component_type' => $componentType,
            //     'admin_id' => Auth::id()
            // ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Log::error('Error Storing Component', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'component_type' => $componentType
            // ]);

            return response()->json(['error' => 'Failed to store component: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $type, $id)
    {
        try {
            // Log::info('Component Update Attempt', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'component_type' => $type,
            //     'component_id' => $id,
            //     'timestamp' => now()
            // ]);

            // Define valid component types
            $validTables = ['cpus', 'gpus', 'motherboards', 'rams', 'storages', 'power_supplies', 'computer_cases'];

            // Validate the component type
            if (!in_array($type, $validTables)) {
                return response()->json(['error' => 'Invalid component type.'], 400);
            }

            // Validate ID
            if (!is_numeric($id)) {
                return response()->json(['error' => 'Invalid ID format'], 400);
            }

            try {
                // Check if component exists
                $component = DB::table($type)->where('id', $id)->first();
                if (!$component) {
                    return response()->json(['error' => 'Component not found'], 404);
                }

                // Get the list of fields for the component type, excluding `id`, `created_at`, and `updated_at`
                $fields = array_diff(Schema::getColumnListing($type), ['id', 'created_at', 'updated_at']);
                $updateData = $request->only($fields); // Only capture valid fields

                // Update `updated_at` timestamp
                $updateData['updated_at'] = now();

                // Handle image upload if an image file is provided
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    
                    // Validate image
                    if (!$image->isValid()) {
                        return response()->json(['error' => 'Invalid image file'], 400);
                    }

                    $folderPath = 'component_images/' . $type;

                    // Delete old image if it exists
                    if ($component->image) {
                        $oldImagePath = storage_path('app/public/' . $component->image);
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    // Generate the image name in the format: type-id.extension
                    $imageExtension = $image->getClientOriginalExtension();
                    $imageName = "{$type}-{$id}.{$imageExtension}";

                    // Store the image in the 'public' storage folder
                    $storedPath = $image->storeAs($folderPath, $imageName, 'public');

                    // Update the image path in `updateData` (relative path)
                    $updateData['image'] = $storedPath;
                }

                // Update the component in the database with explicit ID check
                $updated = DB::table($type)
                            ->where('id', '=', $id)
                            ->update($updateData);

                // Check if the update was successful
                if ($updated) {
                    // Fetch the updated component data
                    $updatedComponent = DB::table($type)->find($id);
                    // Log::info('Component Updated Successfully', [
                    //     'component_id' => $id,
                    //     'component_type' => $type,
                    //     'admin_id' => Auth::id()
                    // ]);
                    return response()->json([
                        'success' => true, 
                        'message' => 'Item updated successfully',
                        'data' => $updatedComponent
                    ]);
                } else {
                    return response()->json(['error' => 'No changes were made to the component'], 200);
                }
            } catch (\Exception $e) {
                // Log::error("Error updating component", [
                //     'error' => $e->getMessage(),
                //     'trace' => $e->getTraceAsString(),
                //     'admin_id' => Auth::id(),
                //     'component_type' => $type,
                //     'component_id' => $id
                // ]);
                return response()->json(['error' => 'Failed to update item.'], 500);
            }
        } catch (\Exception $e) {
            // Log::error('Error Updating Component', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'component_type' => $type,
            //     'component_id' => $id
            // ]);

            return response()->json(['error' => 'Failed to update component: ' . $e->getMessage()], 500);
        }
    }

    public function getComponentData(Request $request)
    {
        try {
            // Log::info('Component Data Retrieval Attempt', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'component_type' => $request->componentType,
            //     'timestamp' => now()
            // ]);

            $componentType = $request->componentType;

            // Define a list of valid component tables
            $validTables = ['cpus', 'gpus', 'motherboards', 'rams', 'storages', 'power_supplies', 'computer_cases'];

            // Check if the selected componentType is valid
            if (in_array($componentType, $validTables)) {
                // Get all column names of the selected table, except created_at and updated_at
                $columns = Schema::getColumnListing($componentType);
                $columns = array_values(array_diff($columns, ['created_at', 'updated_at'])); // Ensure it's a zero-indexed array

                // Fetch column comments
                $comments = [];
                foreach ($columns as $column) {
                    $result = DB::select("SHOW FULL COLUMNS FROM `$componentType` WHERE Field = ?", [$column]);
                    $comments[$column] = $result[0]->Comment ?? '';
                }

                // Fetch all data from the selected table
                $data = DB::table($componentType)->get();

                // Log::info('Component Data Retrieved Successfully', [
                //     'component_type' => $componentType,
                //     'admin_id' => Auth::id()
                // ]);

                return response()->json([
                    'columns' => $columns,
                    'comments' => $comments,
                    'data' => $data
                ]);
            }

            // If the componentType is invalid, return an error response
            return response()->json(['error' => 'Invalid component type selected'], 400);
        } catch (\Exception $e) {
            // Log::error('Error Retrieving Component Data', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'component_type' => $request->componentType
            // ]);

            return response()->json(['error' => 'Failed to retrieve component data: ' . $e->getMessage()], 500);
        }
    }

    public function edit($type, $id)
    {
        try {
            // Log::info('Component Edit Attempt', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'component_type' => $type,
            //     'component_id' => $id,
            //     'timestamp' => now()
            // ]);

            $validTables = ['cpus', 'gpus', 'motherboards', 'rams', 'storages', 'power_supplies', 'computer_cases'];

            if (in_array($type, $validTables)) {
                $item = DB::table($type)->find($id);

                if ($item) {
                    // Log::info('Component Data Retrieved Successfully', [
                    //     'component_id' => $id,
                    //     'component_type' => $type,
                    //     'admin_id' => Auth::id()
                    // ]);
                    // Return JSON response with item data
                    return response()->json(['data' => $item]);
                } else {
                    return response()->json(['error' => 'Item not found'], 404);
                }
            }

            return response()->json(['error' => 'Invalid component type'], 400);
        } catch (\Exception $e) {
            // Log::error('Error Editing Component', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'component_type' => $type,
            //     'component_id' => $id
            // ]);

            return response()->json(['error' => 'Failed to edit component: ' . $e->getMessage()], 500);
        }
    }

    public function delete($type, $id)
    {
        try {
            // Log::info('Component Deletion Attempt', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'component_type' => $type,
            //     'component_id' => $id,
            //     'timestamp' => now()
            // ]);

            $validTables = ['cpus', 'gpus', 'motherboards', 'rams', 'storages', 'power_supplies', 'computer_cases'];

            if (in_array($type, $validTables)) {
                // Retrieve the component record to access the image path
                $component = DB::table($type)->where('id', $id)->first();

                // Check if the component has an associated image and delete it
                if ($component && isset($component->image)) {
                    $imagePath = public_path($component->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Delete the image file
                    }
                }

                // Delete the component from the database
                DB::table($type)->where('id', $id)->delete();

                // Log::info('Component Deleted Successfully', [
                //     'component_id' => $id,
                //     'component_type' => $type,
                //     'admin_id' => Auth::id()
                // ]);

                return response()->json(['success' => 'Item and its image deleted successfully']);
            }

            return response()->json(['error' => 'Invalid component type'], 400);
        } catch (\Exception $e) {
            // Log::error('Error Deleting Component', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'component_type' => $type,
            //     'component_id' => $id
            // ]);

            return response()->json(['error' => 'Failed to delete component: ' . $e->getMessage()], 500);
        }
    }



    public function getColumns($table)
    {
        try {
            // Check if the table exists in the database
            if (!Schema::hasTable($table)) {
                return response()->json(['error' => 'Table not found'], 400);
            }

            // Fetch column names from the table
            $columns = DB::getSchemaBuilder()->getColumnListing($table);

            // Return column names as JSON
            return response()->json(['columns' => $columns]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
