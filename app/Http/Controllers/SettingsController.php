<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SettingsController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the settings dashboard
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * User Management Section
     */
    public function users()
    {
        $this->authorize('viewAny', User::class);

        $users = User::orderBy('name')->paginate(15);

        return view('settings.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,staff,reader',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Log to audit
        $this->logAudit(
            'User Created',
            "New user created: {$user->name} ({$user->email})",
            auth()->id()
        );

        return redirect()->route('settings.users')->with('success', 'User created successfully!');
    }

    public function updateUser(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,staff,reader',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Log to audit
        $this->logAudit(
            'User Updated',
            "User updated: {$user->name} ({$user->email})",
            auth()->id()
        );

        return redirect()->route('settings.users')->with('success', 'User updated successfully!');
    }

    public function destroyUser(User $user)
    {
        $this->authorize('delete', $user);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }

        $name = $user->name;
        $user->delete();

        // Log to audit
        $this->logAudit(
            'User Deleted',
            "User deleted: {$name}",
            auth()->id()
        );

        return redirect()->route('settings.users')->with('success', 'User deleted successfully!');
    }

    /**
     * Profile Settings Section
     */
    public function profile()
    {
        return view('settings.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required_with:new_password',
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Update password if provided
        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()->with('error', 'Current password is incorrect!');
            }
            $user->password = Hash::make($validated['new_password']);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        // Log to audit
        $this->logAudit(
            'Profile Updated',
            "Profile updated for user: {$user->name}",
            $user->id
        );

        return redirect()->route('settings.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Water Rate Settings Section
     */
    public function rates()
    {
        $this->authorize('viewAny', User::class); // Only admins can view rates

        $rates = DB::table('water_rate_settings')
            ->orderBy('id')
            ->get()
            ->keyBy('key');

        return view('settings.rates', compact('rates'));
    }

    public function updateRates(Request $request)
    {
        $this->authorize('viewAny', User::class); // Only admins can update rates

        $validated = $request->validate([
            'minimum_rate' => 'required|numeric|min:0',
            'minimum_consumption' => 'required|numeric|min:0',
            'residential_excess_rate' => 'required|numeric|min:0',
            'commercial_excess_rate' => 'required|numeric|min:0',
            'industrial_excess_rate' => 'required|numeric|min:0',
            'government_excess_rate' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated as $key => $value) {
                DB::table('water_rate_settings')
                    ->where('key', $key)
                    ->update([
                        'value' => $value,
                        'updated_at' => now(),
                    ]);
            }

            // Log to audit
            $this->logAudit(
                'Water Rates Updated',
                "Water rate settings updated: Minimum Rate: ₱{$validated['minimum_rate']}, Residential Excess: ₱{$validated['residential_excess_rate']}, Commercial Excess: ₱{$validated['commercial_excess_rate']}",
                auth()->id()
            );

            DB::commit();

            return redirect()->route('settings.rates')->with('success', 'Water rates updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update rates: ' . $e->getMessage());
        }
    }

    /**
     * Log audit trail
     */
    private function logAudit(string $action, string $details, int $userId)
    {
        DB::table('audit_logs')->insert([
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
            'date_time' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
