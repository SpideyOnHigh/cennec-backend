<a href="{{ route('tickets.list.edit', [$ticket->id,'1']) }}" class="text-reset notification-item read">
    <div class="flex px-4 py-2 hover:bg-gray-50/50 dark:hover:bg-zinc-700/50">
        <div class="flex-grow">
            <h6 class="mb-1 text-gray-700 dark:text-gray-100">{{ $ticket->subject }}</h6>
            <div class="text-gray-600 text-13">
                {{-- <p class="mb-1 dark:text-gray-400">{{ $ticket->message }}</p> --}}
                <div class="flex items-center">
                    <p class="mb-0"><i class="mdi mdi-clock-outline dark:text-gray-400"></i><span>&nbsp;{{ $time }}</span></p>
                    <div class="ml-2 py-[1px] px-1.5 rounded text-xs bg-red-700 text-white inline-block font-medium">Open</div>
                </div>
            </div>
        </div>
    </div>
</a>