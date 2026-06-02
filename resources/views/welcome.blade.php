<x-app-layout>

    {{-- Hero banner --}}
    <div class="-mx-6 -mt-8 mb-10 relative overflow-hidden" style="min-height:360px; background-image:url('/images/bannerwallpaper.jpg'); background-size:cover; background-position:center;">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative z-10 flex flex-col items-center text-center px-6" style="padding-top: 7rem; padding-bottom: 4rem;">
            <p class="text-xs font-semibold text-blue-300 uppercase tracking-widest mb-3">Welcome to TCG Manager!</p>
            <h1 class="text-4xl font-bold text-white mb-4 drop-shadow">Your TCG Tournament Hub</h1>
            <p class="text-white/75 text-base mb-8 max-w-md">Organize and join Trading Card Game tournaments hosted by other users for Yu-Gi-Oh!, Pokémon, Magic: The Gathering, and more!</p>
            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('events.index') }}"
                   class="bg-blue-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-blue-700 transition">
                    Browse Events
                </a>
                @guest
                    <a href="{{ route('register') }}"
                       class="bg-white/10 border border-white/40 text-white text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-white/20 transition">
                        Create Account
                    </a>
                @endguest
            </div>
        </div>
    </div>

    {{-- Feature cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-4">
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">Discover</p>
            <h3 class="text-sm font-bold text-gray-900 mb-2">Browse Events!</h3>
            <p class="text-sm text-gray-500">Find user-organized tournaments for Yu-Gi-Oh!, Pokémon, Magic: The Gathering, and more!</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">Compete</p>
            <h3 class="text-sm font-bold text-gray-900 mb-2">Join & Compete!</h3>
            <p class="text-sm text-gray-500">Register for events with ease and track all your upcoming matches.</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">Organize</p>
            <h3 class="text-sm font-bold text-gray-900 mb-2">Host your own Tournaments!</h3>
            <p class="text-sm text-gray-500">Create and manage your own events, set entry fees, and track participants.</p>
        </div>
    </div>

</x-app-layout>
