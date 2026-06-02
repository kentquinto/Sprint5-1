<nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-screen-2xl mx-auto px-8 h-16 flex items-center justify-between">

        {{-- Logo --}}
        <a href="/" class="flex items-center gap-2 text-blue-600 font-bold text-lg">
            TCG Manager
        </a>

        {{-- Nav links + actions --}}
        <div class="flex items-center gap-6">
            <a href="/" class="text-sm text-gray-600 hover:text-blue-600 transition">Home</a>
            <a href="{{ route('events.index') }}" class="text-sm text-gray-600 hover:text-blue-600 transition">Events</a>

            @auth
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-blue-600 transition">Dashboard</a>

                {{-- Links to the logged-in user's own profile edit page --}}
                <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-blue-600 transition">
                    {{ auth()->user()->name }}
                </a>

                <a href="{{ route('events.create') }}"
                   class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Create Event
                </a>

                {{-- Logout requires a POST form (Laravel CSRF protection) --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                        ➜] Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="border border-blue-600 text-blue-600 text-sm font-medium px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Register
                </a>
            @endauth
        </div>

    </div>
</nav>
