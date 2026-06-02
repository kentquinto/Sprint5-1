<x-app-layout>
    <div class="max-w-sm mx-auto py-12">

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Verify Your Email</h1>
            <p class="text-sm text-gray-500 mt-1">Please verify your email address by clicking the link we sent you.</p>
        </div>

        @if(session('status') == 'verification-link-sent')
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg mb-5">
                A new verification link has been sent to your email.
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                        class="w-full bg-blue-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-blue-700 transition">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full border border-gray-300 text-gray-600 text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-gray-100 transition">
                    Log Out
                </button>
            </form>
        </div>

    </div>
</x-app-layout>
