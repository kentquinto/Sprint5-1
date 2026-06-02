<section>
    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-5">Update Password</h2>

    {{-- Uses a named error bag: $errors->updatePassword --}}
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
            @foreach($errors->updatePassword->get('current_password') as $error)
                <p class="text-red-500 text-xs mt-1">{{ $error }}</p>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">New Password</label>
                <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                @foreach($errors->updatePassword->get('password') as $error)
                    <p class="text-red-500 text-xs mt-1">{{ $error }}</p>
                @endforeach
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Confirm Password</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                @foreach($errors->updatePassword->get('password_confirmation') as $error)
                    <p class="text-red-500 text-xs mt-1">{{ $error }}</p>
                @endforeach
            </div>
        </div>

        <div class="border-t border-gray-100 pt-5 flex items-center gap-4">
            <button type="submit"
                    class="bg-blue-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-blue-700 transition">
                Update Password
            </button>
            @if(session('status') === 'password-updated')
                <p class="text-sm text-green-600">Saved.</p>
            @endif
        </div>
    </form>
</section>
