<x-app-layout>
    <div class="flex flex-col items-center justify-center text-center py-24">

        <p class="text-8xl font-bold text-blue-600 mb-4">404</p>
        <h1 class="text-2xl font-semibold text-gray-800 mb-2">Page Not Found</h1>
        <p class="text-sm text-gray-500 mb-8 max-w-sm">
            The page you're looking for doesn't exist or may have been moved.
        </p>

        <div class="flex gap-3">
            <a href="{{ route('events.index') }}"
               class="bg-blue-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-blue-700 transition">
                Browse Events
            </a>
            <a href="/"
               class="border border-gray-300 text-gray-600 text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-gray-100 transition">
                Go Home
            </a>
        </div>

    </div>
</x-app-layout>
