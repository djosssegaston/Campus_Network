<!-- Alert Component -->
<div class="rounded-lg p-4 mb-6
    @switch($type ?? 'info')
        @case('success')
            bg-green-50 border-l-4 border-green-500
            @break
        @case('error')
            bg-red-50 border-l-4 border-red-500
            @break
        @case('warning')
            bg-yellow-50 border-l-4 border-yellow-500
            @break
        @case('info')
            bg-blue-50 border-l-4 border-blue-500
            @break
    @endswitch
">
    <div class="flex items-start">
        <!-- Icon -->
        <div class="flex-shrink-0">
            @switch($type ?? 'info')
                @case('success')
                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @break
                @case('error')
                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @break
                @case('warning')
                    <svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @break
                @default
                    <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
            @endswitch
        </div>
        
        <!-- Content -->
        <div class="ml-3">
            @if($title ?? false)
                <h3 class="text-sm font-medium
                    @switch($type ?? 'info')
                        @case('success')
                            text-green-800
                            @break
                        @case('error')
                            text-red-800
                            @break
                        @case('warning')
                            text-yellow-800
                            @break
                        @default
                            text-blue-800
                    @endswitch
                    mb-1">
                    {{ $title }}
                </h3>
            @endif
            
            <p class="text-sm
                @switch($type ?? 'info')
                    @case('success')
                        text-green-700
                        @break
                    @case('error')
                        text-red-700
                        @break
                    @case('warning')
                        text-yellow-700
                        @break
                    @default
                        text-blue-700
                @endswitch
            ">
                {{ $slot }}
            </p>
        </div>
        
        <!-- Close button -->
        @if($closeable ?? true)
            <button type="button" class="ml-3 text-gray-400 hover:text-gray-600 transition" onclick="this.parentElement.parentElement.style.display='none'">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        @endif
    </div>
</div>
