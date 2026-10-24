<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\AccountDeletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AccountDeletionController extends Controller
{
    public function destroy(Request $request, AccountDeletion $deletion)
    {
        $data = $request->validate(['current_password' => 'required|string|max:1024', 'confirmation' => 'required|in:DELETE']);
        if (! Hash::check($data['current_password'], $request->user()->getAuthPassword())) {
            throw ValidationException::withMessages(['current_password' => 'Unable to confirm account deletion.']);
        }
        // No submitted user ID is accepted. This action can delete only the authenticated account.
        $deletion->delete($request->user());
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login')->with('success', 'Your account and profile data have been deleted. Site records retain anonymous attribution.');
    }
}
