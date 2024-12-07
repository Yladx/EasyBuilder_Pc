<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningModule; // Assuming you have a model for Learning Modules

class LearningModulesController extends Controller
{
    public function index($id = null)
    {
        // Commented out Laravel logging for learning modules index
        // Uncomment if detailed logging is required
        // \Illuminate\Support\Facades\Log::info('Learning Modules Index Viewed', [
        //     'total_modules' => LearningModule::count(),
        //     'modules_by_tag' => LearningModule::all()->groupBy('tag')->keys()
        // ]);

        // Fetch modules grouped by tag
        $modulesByTag = LearningModule::all()
            ->groupBy('tag');

        // If ID is provided, fetch the specific module
        $selectedModule = null;
        if ($id) {
            $selectedModule = LearningModule::find($id);
        }

        // Pass data to the view
        return view('learningmodules.learnmore', compact('modulesByTag', 'selectedModule'));
    }

    public function fetchmodule($id){
        // Commented out Laravel logging for module fetch
        // Uncomment if detailed logging is required
        // \Illuminate\Support\Facades\Log::info('Learning Module Fetched', [
        //     'module_id' => $id
        // ]);

        $module = LearningModule::find($id);

        return response()->json($module);
    }
}
