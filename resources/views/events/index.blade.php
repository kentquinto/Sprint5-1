<x-app-layout>

@php
    $gameColors = config('game_colors');
    $default    = $gameColors['_default'];
@endphp

{{-- Page banner --}}
<div class="-mx-6 -mt-8 mb-8 relative overflow-hidden" style="min-height:180px; background-image:url('/images/bannerwallpaper.jpg'); background-size:cover; background-position:center;">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 px-12 py-10">
        <h1 class="text-3xl font-bold text-white drop-shadow">All Events</h1>
        <p class="text-white/75 text-sm mt-1">Browse and join TCG events!</p>
    </div>
</div>

{{-- Search / Date / Price / Status filters --}}
<form method="GET" action="{{ route('events.index') }}" class="flex flex-wrap gap-3 mb-4">
    @if(request('game'))
        <input type="hidden" name="game" value="{{ request('game') }}">
    @endif

    <input type="text" name="search" value="{{ request('search') }}" placeholder="Event title..."
           class="border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition w-48">

    <input type="date" name="date" value="{{ request('date') }}"
           class="border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">

    <select name="price" class="border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition w-40">
        <option value="">All Prices</option>
        <option value="free" {{ request('price') === 'free' ? 'selected' : '' }}>Free</option>
        <option value="paid" {{ request('price') === 'paid' ? 'selected' : '' }}>Paid</option>
    </select>

    <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition w-40">
        <option value="">All Statuses</option>
        <option value="upcoming"  {{ request('status') === 'upcoming'  ? 'selected' : '' }}>Upcoming</option>
        <option value="ongoing"   {{ request('status') === 'ongoing'   ? 'selected' : '' }}>Ongoing</option>
        <option value="finished"  {{ request('status') === 'finished'  ? 'selected' : '' }}>Finished</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>

    <button type="submit"
            class="bg-blue-600 text-white text-sm font-medium px-5 py-2 rounded-lg hover:bg-blue-700 transition">
        Filter
    </button>

    @if(request('search') || request('date') || request('price') || request('status'))
        <a href="{{ route('events.index', request('game') ? ['game' => request('game')] : []) }}"
           class="border border-gray-300 text-gray-500 text-sm font-medium px-5 py-2 rounded-lg hover:bg-gray-100 transition">
            Clear
        </a>
    @endif
</form>

{{-- Game filter tabs --}}
<div class="flex gap-2 flex-wrap mb-6">
    <a href="{{ route('events.index') }}"
       class="text-xs font-medium px-4 py-2 rounded-full border transition
              {{ !request('game') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:border-gray-400' }}">
        All Events
    </a>
    @foreach(\App\Models\Game::all() as $game)
        @php $c = $gameColors[$game->name] ?? $default; @endphp
        <a href="{{ route('events.index', ['game' => $game->id]) }}"
           class="text-xs font-medium px-4 py-2 rounded-full border transition
                  {{ request('game') == $game->id
                      ? $c['bg'] . ' ' . $c['text'] . ' ' . $c['border']
                      : 'bg-white text-gray-600 border-gray-300 hover:border-gray-400' }}">
            {{ $game->name }}
        </a>
    @endforeach
</div>

@if($events->isEmpty())
    <div class="bg-white border border-dashed border-gray-300 rounded-lg py-16 text-center">
        <p class="text-sm text-gray-400 mb-4">No events found.</p>
        @auth
            <a href="{{ route('events.create') }}"
               class="bg-blue-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-blue-700 transition">
                + Create Event
            </a>
        @endauth
    </div>
@else

    {{-- Events grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($events as $event)
            @php $c = $gameColors[$event->game->name] ?? $default; @endphp
            <a href="{{ route('events.show', $event) }}"
               class="block bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md hover:border-gray-300 transition">

                {{-- Game banner --}}
                @php
                    $bannerStyle = $c['image']
                        ? 'background-image:url(' . $c['image'] . '); background-size:100%; background-position:center;'
                        : 'background-color:' . $c['color'] . ';';
                @endphp
                <div class="relative h-20" style="{{ $bannerStyle }}">
                    <div class="absolute inset-0 bg-black/30"></div>
                    <div class="relative z-10 h-full flex items-end px-4 pb-2">
                        <span class="text-xs font-bold uppercase tracking-widest text-white drop-shadow">
                            {{ $event->game->name }}
                        </span>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-200"></div>

                {{-- Event details --}}
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        @include('events._status_badge', ['status' => $event->status])
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 mb-3">{{ $event->title }}</h3>
                    <div class="space-y-1 text-xs text-gray-400">
                        <p>📍 {{ $event->location }}</p>
                        <p>📅 {{ $event->date_time->format('M d, Y') }}</p>
                        <p>💰 {{ $event->entry_fee > 0 ? '€'.number_format($event->entry_fee, 2) : 'Free' }}</p>
                        <p>👥 {{ $event->participants->count() }} / {{ $event->max_players }} players</p>
                    </div>
                </div>

            </a>
        @endforeach
    </div>

    @if($events->hasPages())
        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @endif

@endif

</x-app-layout>
