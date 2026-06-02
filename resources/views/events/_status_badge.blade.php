@php
    $config = [
        'upcoming'  => ['classes' => 'bg-yellow-100 text-yellow-800', 'label' => 'Upcoming'],
        'ongoing'   => ['classes' => 'bg-green-100 text-green-800',  'label' => 'Ongoing'],
        'finished'  => ['classes' => 'bg-gray-100 text-gray-600',    'label' => 'Finished'],
        'cancelled' => ['classes' => 'bg-red-100 text-red-800',      'label' => 'Cancelled'],
    ];
    $s = $config[$status] ?? $config['upcoming'];
@endphp
<span class="text-xs font-medium px-3 py-1 rounded-full {{ $s['classes'] }}">
    {{ $s['label'] }}
</span>
