@php
    $images = $getState() ?? collect();
@endphp

@if($images->isNotEmpty())
    <div class="flex flex-wrap gap-1">
        @foreach($images as $image)
            @if($image && $image->image_path)
                <div class="relative group">
                    <img
                        src="{{ $image->image_url }}"
                        alt="Article Image"
                        class="w-10 h-10 object-cover rounded-md border border-gray-200"
                        title="{{ basename($image->image_path) }}"
                    >
                    @if($image->color)
                        <span
                            class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full border border-white shadow"
                            style="background-color: {{ $image->color }};"
                            title="{{ $image->color }}"
                        ></span>
                    @endif
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-md flex items-center justify-center">
                        <a 
                            href="{{ $image->image_url }}" 
                            target="_blank" 
                            class="text-white p-1 hover:text-gray-200"
                            title="View full size"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@else
    <span class="text-gray-400 text-sm">No images</span>
@endif
