@props(['options', 'activeValue', 'action' => 'setFilter'])

<div class="mb-6 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
        @foreach($options as $option)
            <li class="mr-2" role="presentation">
                <button 
                    type="button"
                    wire:click="{{ $action }}({{ is_null($option['value']) ? 'null' : $option['value'] }})"
                    class="inline-block p-4 border-b-2 rounded-t-lg transition-colors duration-200 
                    {{ $activeValue == $option['value'] 
                        ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500 bg-blue-50/50 dark:bg-blue-900/10' 
                        : 'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400 border-transparent' 
                    }}"
                >
                    {{ $option['label'] }}
                </button>
            </li>
        @endforeach
    </ul>
</div>
