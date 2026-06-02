<x-app-layout>

    <a href="{{ route('events.show', $event) }}" class="text-sm text-gray-500 hover:text-blue-600 transition mb-6 inline-block">
        ← Back to Event
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Event</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $event->title }}</p>
    </div>

    {{-- Submits to EventController@update--}}
    <form method="POST" action="{{ route('events.update', $event) }}">
        @csrf
        @method('PUT')

        <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">

            {{-- Title + Game --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Event Title</label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Game</label>
                    <select name="game_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                        @foreach($games as $game)
                            <option value="{{ $game->id }}" {{ old('game_id', $event->game_id) == $game->id ? 'selected' : '' }}>
                                {{ $game->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('game_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">{{ old('description', $event->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Location --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Location</label>
                <input type="text" name="location" value="{{ old('location', $event->location) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Date + Max Players + Entry Fee --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Date & Time</label>
                    {{-- Carbon formats the stored date into datetime-local format (Y-m-d\TH:i) --}}
                    <input type="datetime-local" name="date_time"
                           value="{{ old('date_time', $event->date_time->format('Y-m-d\TH:i')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                    @error('date_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Max Players</label>
                    <input type="number" name="max_players" value="{{ old('max_players', $event->max_players) }}" min="2"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                    @error('max_players') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Entry Fee (€)</label>
                    <input type="number" name="entry_fee" value="{{ old('entry_fee', $event->entry_fee) }}" min="0" step="0.01"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                    @error('entry_fee') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Status</label>
                <select name="status"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                    @foreach(['upcoming', 'ongoing', 'finished', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', $event->status) === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <div class="border-t border-gray-100 pt-5 flex gap-3">
                <button type="submit"
                        class="bg-blue-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-blue-700 transition">
                    Save Changes
                </button>
                <a href="{{ route('events.show', $event) }}"
                   class="border border-gray-300 text-gray-600 text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-gray-100 transition">
                    Cancel
                </a>
            </div>

        </div>
    </form>

</x-app-layout>
