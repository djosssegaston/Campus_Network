<!-- Button Component -->
<button 
    type="{{ $type ?? 'button' }}"
    class="w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed
        @switch($variant ?? 'primary')
            @case('primary')
                bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:shadow-lg hover:from-blue-700 hover:to-blue-800 focus:ring-blue-500
                @break
            @case('secondary')
                bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-500
                @break
            @case('danger')
                bg-red-600 text-white hover:bg-red-700 focus:ring-red-500
                @break
            @case('success')
                bg-green-600 text-white hover:bg-green-700 focus:ring-green-500
                @break
            @case('outline')
                border-2 border-gray-300 text-gray-700 hover:border-gray-400 hover:bg-gray-50 focus:ring-gray-500
                @break
        @endswitch
    "
    @if($disabled ?? false) disabled @endif
>
    @if($icon ?? false)
        <span class="inline mr-2">{{ $icon }}</span>
    @endif
    {{ $slot }}
</button>
