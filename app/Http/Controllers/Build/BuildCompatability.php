<?php

namespace App\Http\Controllers\Build;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildCompatability extends Controller
{
    // Fetch all Motherboards
    public function getAllMotherboards()
    {
        return DB::table('motherboards')->get(); // Fetch all Motherboards
    }

    // Fetch all Power Supplies
    public function getAllPowerSupplies()
    {
        return DB::table('power_supplies')->get(); // Fetch all Power Supplies
    }

    public function getCompatibleCpus($motherboardId)
    {
        $motherboard = DB::table('motherboards')->find($motherboardId);

        if (!$motherboard) {
            return response()->json([], 404);
        }

        $cpus = DB::table('cpus')->where('socket', $motherboard->socket)->get();
        return response()->json($cpus);
    }

    public function getCompatibleGpus($motherboardId)
    {
        $motherboard = DB::table('motherboards')->find($motherboardId);

        if (!$motherboard) {
            return response()->json([], 404);
        }

        $gpus = DB::table('gpus')->where('pcie_slots_required', '<=', $motherboard->pcie_slots)->get();
        return response()->json($gpus);
    }

    public function getCompatibleRams($motherboardId)
    {
        $motherboard = DB::table('motherboards')->find($motherboardId);

        if (!$motherboard) {
            return response()->json([], 404);
        }

        $rams = DB::table('rams')
            ->select('rams.*', 'speed_mhz as speed_mhz')  // Explicitly select speed_mhz
            ->where('ram_generation', $motherboard->ram_generation)
            ->where('speed_ddr_version', '<=', $motherboard->max_memory)
            ->get();
        return response()->json($rams);
    }
    public function getCompatibleCases($motherboardId, $gpuId)
    {
        // Fetch the motherboard and GPU from the database
        $motherboard = DB::table('motherboards')->find($motherboardId);
        $gpu = DB::table('gpus')->find($gpuId);

        // Validate motherboard existence
        if (!$motherboard) {
            return response()->json(['error' => 'Motherboard not found'], 404);
        }

        // Validate GPU existence
        if (!$gpu) {
            return response()->json(['error' => 'GPU not found'], 404);
        }

        // Fetch compatible cases based on form factor and GPU length
        $compatibleCases = DB::table('computer_cases')
            ->where('form_factor', $motherboard->form_factor) // Match motherboard form factor
            ->where('gpu_length_limit', '>=', $gpu->length)   // Ensure GPU fits within case's limit
            ->get();

        if ($compatibleCases->isEmpty()) {
            return response()->json(['message' => 'No compatible cases found'], 404);
        }

        return response()->json($compatibleCases);
    }

    public function getCompatibleStorages($motherboardId)
    {
        $motherboard = DB::table('motherboards')->find($motherboardId);

        if (!$motherboard) {
            return response()->json([], 404);
        }

        $interfaces = explode(',', $motherboard->storage_interface);
        $query = DB::table('storages');
        foreach ($interfaces as $interface) {
            $query->orWhere('interface', 'LIKE', '%' . trim($interface) . '%');
        }

        return response()->json($query->get());
    }

    public function getCompatiblePowerSupplies(Request $request)
    {
        $totalTdp = $request->input('totalTdp') * 1.3;
        // Validate totalTdp
        if (!is_numeric($totalTdp) || $totalTdp <= 0) {
            return response()->json(['error' => 'Invalid total TDP'], 400);
        }

        try {
            // Fetch compatible power supplies
            $powerSupplies = DB::table('power_supplies')
                ->where('max_tdp', '>=', $totalTdp)
                ->get();

            if ($powerSupplies->isEmpty()) {
                return response()->json(['message' => 'No compatible power supplies found'], 404);
            }

            return response()->json($powerSupplies);

        } catch (\Exception $e) {
            // Log the exception

            return response()->json(['error' => 'Server error. Please try again later.'], 500);
        }
    }


}



