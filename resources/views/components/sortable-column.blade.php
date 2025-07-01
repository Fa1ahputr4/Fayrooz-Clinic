<th 
    wire:click="sortBy('{{ $field }}')" 
    class="py-3 px-4 cursor-pointer text-center"
>
    <div class="inline-flex items-center justify-center gap-1">
        <span class="mr-2">{{ $label }}</span>
        <div class="relative h-3 w-3">
            <i class="fas fa-sort-up absolute top-0 left-0 text-[12px]
                {{ $currentField === $field && $currentDirection === 'asc' ? 'text-gray-500' : 'text-gray-300' }}">
            </i>
            <i class="fas fa-sort-down absolute bottom-0 left-0 text-[12px]
                {{ $currentField === $field && $currentDirection === 'desc' ? 'text-gray-500' : 'text-gray-300' }}">
            </i>
        </div>
    </div>
</th>