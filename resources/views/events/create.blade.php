<x-app-layout>

    <a href="{{ route('events.index') }}" class="text-sm text-gray-500 hover:text-blue-600 transition mb-6 inline-block">
        ← Back to Events
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create an Event</h1>
        <p class="text-sm text-gray-500 mt-1">Organize your own TCG tournament!</p>
    </div>

    {{-- Submits to EventController@store --}}
    <form method="POST" action="{{ route('events.store') }}">
        @csrf

        <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">

            {{-- Title + Game --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Event Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Local tournament"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Game</label>
                    <select name="game_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                        <option value="">Select a game</option>
                        @foreach($games as $game)
                            <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
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
                <textarea name="description" rows="4" placeholder="Describe your event..."
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Location --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Location</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g. Barcelona Game Store"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Date + Max Players + Entry Fee --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Date & Time</label>
                    <input type="datetime-local" name="date_time" value="{{ old('date_time') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                    @error('date_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Max Players</label>
                    <input type="number" name="max_players" value="{{ old('max_players') }}" min="2" placeholder="16"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                    @error('max_players') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Entry Fee (€)</label>
                    <input type="number" name="entry_fee" value="{{ old('entry_fee', 0) }}" min="0" step="0.01" placeholder="0.00"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-blue-500 transition">
                    @error('entry_fee') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="border-t border-gray-100 pt-5 flex gap-3">
                <button type="submit"
                        class="bg-blue-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-blue-700 transition">
                    Create Event
                </button>
                <a href="{{ route('events.index') }}"
                   class="border border-gray-300 text-gray-600 text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-gray-100 transition">
                    Cancel
                </a>
            </div>

        </div>
    </form>

</x-app-layout>
