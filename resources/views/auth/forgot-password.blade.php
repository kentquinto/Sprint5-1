<x-app-layout>
    <div class="max-w-sm mx-auto py-12">

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Forgot Password</h1>
            <p class="text-sm text-gray-500 mt-1">Enter your email and we'll send you a reset link.</p>
        </div>

        @if(session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg mb-5">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <button type="submit"
                            class="w-full bg-blue-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-blue-700 transition">
                        Send Reset Link
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500 mt-5">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Back to Login</a>
        </p>

    </div>
</x-app-layout>
