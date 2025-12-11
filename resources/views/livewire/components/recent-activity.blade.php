<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-semibold mb-4">Actividad Reciente</h3>
        
        @if(count($activities) > 0)
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach($activities as $loop_index => $activity)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-800
                                        {{ $activity->action === 'created' ? 'bg-green-500' : '' }}
                                        {{ $activity->action === 'updated' ? 'bg-blue-500' : '' }}
                                        {{ $activity->action === 'deleted' ? 'bg-red-500' : '' }}
                                        {{ !in_array($activity->action, ['created', 'updated', 'deleted']) ? 'bg-gray-500' : '' }}
                                    ">
                                        @if($activity->action === 'created')
                                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        @elseif($activity->action === 'updated')
                                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        @elseif($activity->action === 'deleted')
                                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $activity->description }}
                                        </p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                        <time datetime="{{ $activity->created_at }}">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400 text-sm">No hay actividad reciente.</p>
        @endif
    </div>
</div>
