@props(['value' => '', 'model' => 'selectedItems'])

<td class="w-4 p-4">
    <input 
        wire:model.live="{{ $model }}" 
        value="{{ $value }}" 
        type="checkbox" 
        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
        {{ $attributes }}
    >
</td>
