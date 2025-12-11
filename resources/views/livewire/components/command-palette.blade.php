<div 
    x-data="{ 
        open: @entangle('isOpen'),
        selectedIndex: 0,
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => this.$refs.searchInput.focus());
            }
        },
        selectNext() {
            if (this.selectedIndex < {{ count($results) }} - 1) {
                this.selectedIndex++;
            }
        },
        selectPrev() {
            if (this.selectedIndex > 0) {
                this.selectedIndex--;
            }
        },
        selectResult() {
            const selected = this.$refs.resultsList.children[this.selectedIndex];
            if (selected) {
                selected.click();
            }
        }
    }"
    @keydown.window.ctrl.k.prevent="toggle()"
    @keydown.window.cmd.k.prevent="toggle()"
    @keydown.escape.window="open = false"
    x-show="open"
    class="relative z-50"
    role="dialog"
    aria-modal="true"
    style="display: none;"
>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-25 transition-opacity" @click="open = false"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20">
        <div 
            class="mx-auto max-w-xl transform divide-y divide-gray-100 overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-2xl ring-1 ring-black ring-opacity-5 transition-all"
            @click.away="open = false"
        >
            <div class="relative">
                <!-- Search Icon -->
                <svg class="pointer-events-none absolute top-3.5 left-4 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
                
                <input 
                    x-ref="searchInput"
                    wire:model.live.debounce.300ms="search"
                    @keydown.arrow-down.prevent="selectNext()"
                    @keydown.arrow-up.prevent="selectPrev()"
                    @keydown.enter.prevent="selectResult()"
                    type="text" 
                    class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-0 sm:text-sm" 
                    placeholder="Buscar páginas, usuarios o acciones..."
                    role="combobox" 
                    aria-expanded="false" 
                    aria-controls="options"
                >
            </div>

            @if(count($results) > 0)
                <ul x-ref="resultsList" class="max-h-72 scroll-py-2 overflow-y-auto py-2 text-sm text-gray-800 dark:text-gray-200" id="options" role="listbox">
                    @foreach($results as $index => $result)
                        <li 
                            class="cursor-pointer select-none px-4 py-2" 
                            :class="{ 'bg-indigo-600 text-white': selectedIndex === {{ $index }}, 'hover:bg-gray-100 dark:hover:bg-gray-700': selectedIndex !== {{ $index }} }"
                            role="option" 
                            tabindex="-1"
                            @click="window.location.href = '{{ $result['url'] }}'"
                            @mouseover="selectedIndex = {{ $index }}"
                        >
                            <div class="flex items-center">
                                <!-- Icon logic based on type -->
                                <div class="flex-shrink-0 mr-3">
                                    @if($result['icon'] === 'home')
                                        <i class="fas fa-home"></i>
                                    @elseif($result['icon'] === 'users' || $result['icon'] === 'user')
                                        <i class="fas fa-user"></i>
                                    @elseif($result['icon'] === 'calendar')
                                        <i class="fas fa-calendar"></i>
                                    @elseif($result['icon'] === 'dumbbell')
                                        <i class="fas fa-dumbbell"></i>
                                    @elseif($result['icon'] === 'cube')
                                        <i class="fas fa-cube"></i>
                                    @else
                                        <i class="fas fa-link"></i>
                                    @endif
                                </div>
                                <span class="flex-auto truncate">{{ $result['title'] }}</span>
                                <span class="ml-3 flex-none text-xs text-gray-400" :class="{ 'text-indigo-200': selectedIndex === {{ $index }} }">
                                    {{ $result['type'] }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @elseif(strlen($search) > 1)
                <p class="p-4 text-sm text-gray-500">No se encontraron resultados.</p>
            @else
                <div class="py-14 px-6 text-center text-sm sm:px-14">
                    <svg class="mx-auto h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59" />
                    </svg>
                    <p class="mt-4 font-semibold text-gray-900 dark:text-white">Comandos Rápidos</p>
                    <p class="mt-2 text-gray-500">Busca usuarios y navega por el sistema rápidamente.</p>
                </div>
            @endif
        </div>
    </div>
</div>
