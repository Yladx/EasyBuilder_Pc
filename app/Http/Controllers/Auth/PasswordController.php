<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Log the password change activity
        DB::table('activity_logs')->insert([
            'user_id' => $request->user()->id,
            'activity_timestamp' => now(),
            'action' => 'update',
            'type' => 'user',
            'activity' => 'Password changed',
            'activity_details' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('status', 'password-updated');
    }
}
