<x-app-layout>

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-sm text-gray-500 mb-1">Welcome back!</p>
        <h1 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- Events created by the logged-in user --}}
        <div>
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Created events</h2>

            @forelse($createdEvents as $event)
                <a href="{{ route('events.show', $event) }}"
                   class="flex items-center justify-between bg-white border border-gray-200 rounded-lg px-4 py-3 mb-3 hover:border-blue-400 transition">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $event->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $event->game->name }} &middot; {{ $event->date_time->format('M d, Y') }}
                        </p>
                    </div>
                    @include('events._status_badge', ['status' => $event->status])
                </a>
            @empty
                <div class="bg-white border border-dashed border-gray-300 rounded-lg px-4 py-6 text-center">
                    <p class="text-sm text-gray-400 mb-3">No events created yet.</p>
                    <a href="{{ route('events.create') }}"
                       class="bg-blue-600 text-white text-xs font-medium px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        + Create your first event
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Events the logged-in user has joined (User->participatingEvents relationship) --}}
        <div>
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Joined events</h2>

            @forelse($participatingEvents as $event)
                <a href="{{ route('events.show', $event) }}"
                   class="flex items-center justify-between bg-white border border-gray-200 rounded-lg px-4 py-3 mb-3 hover:border-blue-400 transition">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $event->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $event->game->name }} &middot; {{ $event->date_time->format('M d, Y') }}
                        </p>
                    </div>
                    @include('events._status_badge', ['status' => $event->status])
                </a>
            @empty
                <div class="bg-white border border-dashed border-gray-300 rounded-lg px-4 py-6 text-center">
                    <p class="text-sm text-gray-400 mb-3">You haven't joined any events yet.</p>
                    <a href="{{ route('events.index') }}"
                       class="border border-blue-600 text-blue-600 text-xs font-medium px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                        Browse Events
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
