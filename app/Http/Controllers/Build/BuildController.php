<?php

namespace App\Http\Controllers\Build;

use App\Http\Controllers\Controller;

use App\Http\Requests\Build\BuildUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Build;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Motherboard;
use App\Models\Cpu;
use App\Models\Gpu;
use App\Models\Ram;
use App\Models\Storage;
use App\Models\PowerSupply;
use App\Models\ComputerCase;

class BuildController extends Controller
{

    public function buildPc()
    {
        // Fetch all components
        $motherboards = Motherboard::all();
        $cpus = Cpu::all();
        $gpus = Gpu::all();
        $rams = Ram::all();
        $storages = Storage::all();
        $psus = PowerSupply::all();
        $cases = ComputerCase::all();

        return view('builds.buildpc', compact(
            'motherboards',
            'cpus',
            'gpus',
            'rams',
            'storages',
            'psus',
            'cases'
        ));
    }

    public function update(BuildUpdateRequest $request, $id)
    {
        // Find the build and check for ownership or null user_id
        $build = Build::where('id', $id)
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                      ->orWhereNull('user_id');
            })
            ->firstOrFail();

        // Update the build with validated data
        $build->update($request->validated());

        // Log the update activity only if the build has a user_id
        if ($build->user_id) {
            // Commented out Laravel logging for build update
            // Uncomment if detailed logging is required
            // \Illuminate\Support\Facades\Log::info('Build Updated', [
            //     'user_id' => Auth::id(),
            //     'build_id' => $build->id,
            //     'build_name' => $build->build_name
            // ]);

            DB::table('activity_logs')->insert([
                'user_id' => Auth::id(),
                'build_id' => $build->id,
                'activity_timestamp' => now(),
                'action' => 'update',
                'type' => 'build',
                'activity' => 'Build updated',
                'activity_details' => 'Build ID: ' . $build->id . ', Name: ' . $build->build_name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Build updated successfully!');
    }



    public function deleteBuild($id)
    {
        // Find the build
        $build = Build::findOrFail($id);

        // If build not found
        if (!$build) {
            return redirect()->back()->with('error', 'Build not found.');
        }

        // Log the build deletion activity only if a user is logged in
        if (Auth::check()) {
            // Commented out Laravel logging for build deletion
            // Uncomment if detailed logging is required
            // \Illuminate\Support\Facades\Log::warning('Build Deleted', [
            //     'user_id' => Auth::id(),
            //     'build_id' => $build->id,
            //     'build_name' => $build->build_name
            // ]);

            DB::table('activity_logs')->insert([
                'user_id' => Auth::id(),
                'build_id' => $build->id,
                'activity_timestamp' => now(),
                'action' => 'delete',
                'type' => 'build',
                'activity' => 'Build deleted',
                'activity_details' => 'Build ID: ' . $build->id . ', Name: ' . $build->build_name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Delete the build
        $build->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Build deleted successfully.');
    }


}
