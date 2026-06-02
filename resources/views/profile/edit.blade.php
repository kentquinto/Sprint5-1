<x-app-layout>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your account information</p>
    </div>

    <div class="space-y-6">

        {{-- name, email, country, favourite game, bio --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- change password --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            @include('profile.partials.update-password-form')
        </div>

        {{--delete account --}}
        <div class="bg-white border border-red-200 rounded-xl p-6">
            @include('profile.partials.delete-user-form')
        </div>

    </div>

</x-app-layout>
