<!-- Form Textarea Component -->
<div>
    <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700 mb-1.5">
        {{ $label ?? ucfirst($name) }}
        @if($required ?? false)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <textarea 
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        rows="{{ $rows ?? 4 }}"
        placeholder="{{ $placeholder ?? '' }}"
        class="w-full px-4 py-2.5 text-sm rounded-lg border-2 transition-all focus:outline-none focus:ring-2 focus:ring-offset-0 resize-none
            @error($name)
                border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500
            @else
                border-gray-300 hover:border-gray-400 focus:border-blue-500 focus:ring-blue-500
            @enderror"
        @if($required ?? false) required @endif
        @if($disabled ?? false) disabled @endif
    >{{ old($name, $value ?? '') }}</textarea>
    
    @error($name)
        <p class="mt-1.5 text-sm text-red-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 14.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.101 8.101a1 1 0 001.414 0l9.101-9.101z" clip-rule="evenodd"/>
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>
