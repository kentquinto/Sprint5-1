<x-app-layout>
    <div class="max-w-sm mx-auto py-12">

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Confirm Password</h1>
            <p class="text-sm text-gray-500 mt-1">Please confirm your password before continuing.</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">
            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <button type="submit"
                            class="w-full bg-blue-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-blue-700 transition">
                        Confirm
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
