<x-app-layout>

    {{-- Profile header card --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Player Profile</p>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $user->name }}</h1>
                @if($user->country)
                    <p class="text-sm text-gray-400">📍 {{ $user->country }}</p>
                @endif
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-blue-600">{{ $finishedEvents->count() }}</p>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Events Finished</p>
            </div>
        </div>

        {{-- Bio + Favourite Game --}}
        @if($user->bio || $user->favoriteGame)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6 pt-6 border-t border-gray-100">
                @if($user->bio)
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Bio</p>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $user->bio }}</p>
                    </div>
                @endif
                @if($user->favoriteGame)
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Favourite Game</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $user->favoriteGame->name }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Active events: upcoming + ongoing (from ProfileController@show) --}}
    @if($upcomingEvents->isNotEmpty())
        <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">
                Active Events ({{ $upcomingEvents->count() }})
            </h3>
            <div class="space-y-3">
                @foreach($upcomingEvents as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 hover:border-blue-400 transition">
                        <div>
                            <p class="text-xs font-semibold text-blue-600 mb-0.5">{{ $event->game->name }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $event->title }}</p>
                        </div>
                        @include('events._status_badge', ['status' => $event->status])
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Finished events --}}
    @if($finishedEvents->isNotEmpty())
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">
                Finished Events ({{ $finishedEvents->count() }})
            </h3>
            <div class="space-y-3">
                @foreach($finishedEvents as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 hover:border-blue-400 transition">
                        <div>
                            <p class="text-xs font-semibold text-blue-600 mb-0.5">{{ $event->game->name }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $event->title }}</p>
                        </div>
                        @include('events._status_badge', ['status' => $event->status])
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($upcomingEvents->isEmpty() && $finishedEvents->isEmpty())
        <div class="bg-white border border-dashed border-gray-300 rounded-xl p-10 text-center">
            <p class="text-sm text-gray-400">This player hasn't joined any events yet.</p>
        </div>
    @endif

</x-app-layout>
