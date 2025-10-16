@props(['align' => 'right'])

<td class="px-6 py-4 text-{{ $align }}">
    <div class="flex gap-3 justify-{{ $align }}">
        {{ $slot }}
    </div>
</td>
