@csrf

@if(isset($user))
    @method('PUT')
@endif

<div class="space-y-6 bg-white dark:bg-gray-800 px-4 py-5 sm:p-6 rounded-lg shadow">
    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
        <!-- Name -->
        <div class="sm:col-span-3">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Full Name <span class="text-red-500">*</span>
            </label>
            <div class="mt-1">
                <input type="text" name="name" id="name" autocomplete="name" 
                       value="{{ old('name', $user->name ?? '') }}"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2 px-3 h-10"
                       style="min-height: 42px;"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Email -->
        <div class="sm:col-span-3">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Email Address <span class="text-red-500">*</span>
            </label>
            <div class="mt-1">
                <input type="email" name="email" id="email" autocomplete="email"
                       value="{{ old('email', $user->email ?? '') }}" 
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2 px-3 h-10"
                       style="min-height: 42px;"
                       {{ isset($user) ? 'readonly' : 'required' }}>
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Role -->
        <div class="sm:col-span-3">
            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Role <span class="text-red-500">*</span>
            </label>
            <div class="mt-1">
                <select id="role" name="role" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2 px-3 h-10"
                        style="min-height: 42px;"
                        required>
                    <option value="" disabled {{ !isset($user) ? 'selected' : '' }}>Select a role</option>
                    @foreach(\App\Models\User::ROLES as $key => $label)
                        <option value="{{ $key }}" {{ old('role', $user->role ?? '') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="sm:col-span-3">
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ isset($user) ? 'New ' : '' }}Password @if(!isset($user))<span class="text-red-500">*</span>@endif
            </label>
            <div class="mt-1">
                <input type="password" name="password" id="password" autocomplete="new-password"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2 px-3 h-10"
                       style="min-height: 42px;"
                       {{ !isset($user) ? 'required' : '' }}>
                @error('password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                @if(isset($user))
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank to keep current password</p>
                @endif
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="sm:col-span-3">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Confirm {{ isset($user) ? 'New ' : '' }}Password @if(!isset($user))<span class="text-red-500">*</span>@endif
            </label>
            <div class="mt-1">
                <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2 px-3 h-10"
                       style="min-height: 42px;"
                       {{ !isset($user) ? 'required' : '' }}>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="pt-5">
        <div class="flex justify-end space-x-3">
            <a href="{{ route('users.index') }}" 
               class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ isset($user) ? 'Update' : 'Create' }} User
            </button>
        </div>
    </div>
</div>
