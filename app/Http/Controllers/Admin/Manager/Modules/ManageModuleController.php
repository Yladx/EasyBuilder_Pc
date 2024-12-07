<?php

namespace App\Http\Controllers\Admin\Manager\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\LearningModuleRequest;
use Illuminate\Http\JsonResponse;
use App\Models\LearningModule;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ManageModuleController extends Controller
{
    public function indexModules(Request $request)
    {
        try {
            // Log::info('Learning Modules Management Page Access', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'search_query' => $request->input('search', 'N/A'),
            //     'filter_tag' => $request->input('tag', 'N/A'),
            //     'timestamp' => now()
            // ]);

            // Fetch all modules
            $modules = LearningModule::all();

            // Calculate the total number of modules
            $totalModules = $modules->count();

            // Extract tags from each module (assumes comma-separated values in the 'tag' column)
            $allTags = $modules->flatMap(function ($module) {
                return explode(',', $module->tag); // Split the tags by commas
            })->unique()->values(); // Get unique tags

            // Log::debug('Learning Modules Retrieved', [
            //     'total_modules' => $totalModules,
            //     'search_applied' => $request->has('search'),
            //     'tag_filter_applied' => $request->has('tag')
            // ]);

            if (request()->ajax()) {
                return response()->json([
                    'tags' => $allTags,
                    'statistics' => ['totalModules' => $totalModules],
                    'modules' => $modules,
                ]);

            }
            // Pass data to the view
            return view('admin.content.manage-modules', [
                'tags' => $allTags,
                'statistics' => ['totalModules' => $totalModules],
                'modules' => $modules,

            ]);
        } catch (\Exception $e) {
            // Log::error('Error Accessing Learning Modules Management Page', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id()
            // ]);

            return back()->with('error', 'Unable to load learning modules management page.');
        }
    }

    public function getModulesByTag(string $tag): JsonResponse
    {
        try {
            // Log::info('Learning Module Retrieval by Tag', [
            //     'admin_id' => Auth::id(),
            //     'tag' => $tag,
            //     'timestamp' => now()
            // ]);

            // Retrieve modules that match the given tag
            $modules = LearningModule::where('tag', 'LIKE', "%{$tag}%")->get();

            // Log::debug('Learning Modules Retrieved by Tag', [
            //     'tag' => $tag,
            //     'modules_count' => $modules->count()
            // ]);

            return response()->json([
                'success' => true,
                'tag' => $tag,
                'modules' => $modules,
            ]);
        } catch (\Exception $e) {
            // Log::error('Error Retrieving Learning Modules by Tag', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'tag' => $tag
            // ]);

            return response()->json(['success' => false, 'message' => 'Failed to retrieve modules by tag']);
        }
    }

    public function destroy($id)
    {
        // Log the incoming request method
        // \Log::info('Destroy method called', [
        //     'method' => request()->method(),
        //     'id' => $id,
        //     'all_input' => request()->all()
        // ]);

        try {
            // Find the module by ID
            $module = LearningModule::findOrFail($id);

            // Delete the associated video file if it exists
            if ($module->video_src && Storage::exists('public/' . $module->video_src)) {
                Storage::delete('public/' . $module->video_src);
            }

            // Delete the module record
            $module->delete();

            return response()->json([
                'success' => true, 
                'message' => 'Module deleted successfully'
            ]);
        } catch (\Exception $e) {
            // Log the full exception
            // \Log::error('Error in destroy method', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString()
            // ]);

            return response()->json([
                'success' => false, 
                'message' => 'Failed to delete module: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        try {
            // Log::info('Learning Module Creation Form Access', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'timestamp' => now()
            // ]);

            // Fetch unique tags from the database
            $existingTags = LearningModule::pluck('tag')->unique();

            // Log::debug('Learning Module Creation Form Loaded', [
            //     'existing_tags_count' => $existingTags->count()
            // ]);

            // Pass the existing tags to the view
            return view('admin.content.partials.add-module-form', compact('existingTags'));
        } catch (\Exception $e) {
            // Log::error('Error Accessing Learning Module Creation Form', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id()
            // ]);

            return back()->with('error', 'Unable to load learning module creation form.');
        }
    }

    public function edit($id)
    {
        try {
            // Log::info('Learning Module Edit Form Access', [
            //     'admin_id' => Auth::id(),
            //     'module_id' => $id,
            //     'timestamp' => now()
            // ]);

            // Fetch the module to be edited
            $module = LearningModule::findOrFail($id);

            // Fetch unique tags from the database
            $existingTags = LearningModule::pluck('tag')->unique();

            // Log::debug('Learning Module Edit Form Loaded', [
            //     'existing_tags_count' => $existingTags->count()
            // ]);

            // Pass the module and existing tags to the view
            return view('admin.content.partials.edit-module-form', compact('module', 'existingTags'));
        } catch (\Exception $e) {
            // Log::error('Error Accessing Learning Module Edit Form', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'module_id' => $id
            // ]);

            return back()->with('error', 'Unable to load learning module edit form.');
        }
    }

    public function store(LearningModuleRequest $request)
    {
        try {
            // Log::info('Learning Module Creation Attempt', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'module_title' => $request->input('title'),
            //     'timestamp' => now()
            // ]);

            $validatedData = $request->validated();

            // Determine the tag: use new_tag if provided, otherwise use selected tag
            $tag = $validatedData['new_tag'] ?? $validatedData['tag'];

            // Handle video file upload if provided
            $videoPath = null;
            if ($request->hasFile('video_src')) {
                $videoPath = $request->file('video_src')->store('videos', 'public'); // Store in `storage/app/public/videos`
            }

            // Create the module
            $module = new LearningModule();
            $module->tag = $tag;
            $module->title = $validatedData['title'];
            $module->description = $validatedData['description'];
            $module->video_src = $videoPath;
            $module->information = $validatedData['information'];
            $module->save();

            // Log::info('Learning Module Created Successfully', [
            //     'module_id' => $module->id,
            //     'module_title' => $module->title,
            //     'admin_id' => Auth::id()
            // ]);

            return redirect()->back()->with('success', 'Learning module created successfully.');
        } catch (\Exception $e) {
            // Log::error('Error Creating Learning Module', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id()
            // ]);

            return redirect()->back()->with('error', 'Failed to create learning module: ' . $e->getMessage());
        }
    }

    public function update(LearningModuleRequest $request, $id)
    {
        try {
            // Log::info('Learning Module Update Attempt', [
            //     'admin_id' => Auth::id(),
            //     'module_id' => $id,
            //     'module_title' => $request->input('title'),
            //     'update_fields' => array_keys($request->except(['_token', 'image'])),
            //     'timestamp' => now()
            // ]);

            $validatedData = $request->validated();

            // Fetch the module to update
            $module = LearningModule::findOrFail($id);

            // Determine the tag: use new_tag if provided, otherwise use selected tag
            $tag = $validatedData['new_tag'] ?? $validatedData['tag'];

            // Handle video file upload if provided
            if ($request->hasFile('video_src')) {
                // Delete old video file if exists
                if ($module->video_src && Storage::disk('public')->exists($module->video_src)) {
                    Storage::disk('public')->delete($module->video_src);
                }
                $videoPath = $request->file('video_src')->store('videos', 'public');
                $module->video_src = $videoPath;
            }

            // Update the module details
            $module->tag = $tag;
            $module->title = $validatedData['title'];
            $module->description = $validatedData['description'];
            $module->information = $validatedData['information'];
            $module->save();

            // Log::info('Learning Module Updated Successfully', [
            //     'module_id' => $module->id,
            //     'module_title' => $module->title,
            //     'admin_id' => Auth::id()
            // ]);

            return redirect()->back()->with('success', 'Learning module updated successfully.');
        } catch (\Exception $e) {
            // Log::error('Error Updating Learning Module', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'module_id' => $id
            // ]);

            return redirect()->back()->with('error', 'Failed to update learning module: ' . $e->getMessage());
        }
    }
}
