<section>
    <h2 class="text-sm font-semibold text-red-500 uppercase tracking-wide mb-1">Delete Account</h2>
    <p class="text-sm text-gray-500 mb-5">Once deleted, all your data will be permanently removed.</p>

    {{-- Submits to ProfileController@destroy, also requires password confirmation. --}}
    <form method="post" action="{{ route('profile.destroy') }}"
          onsubmit="return confirm('Are you sure you want to delete your account?')" class="space-y-5">
        @csrf
        @method('delete')

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Confirm Password</label>
            <input id="password" name="password" type="password" placeholder="Enter your password"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-red-400 transition">
            @foreach($errors->userDeletion->get('password') as $error)
                <p class="text-red-500 text-xs mt-1">{{ $error }}</p>
            @endforeach
        </div>

        <div>
            <button type="submit"
                    class="bg-red-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-red-700 transition">
                Delete Account
            </button>
        </div>
    </form>
</section>
