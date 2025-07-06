{{-- <div class="inline-flex w-full rounded-md" role="group">
    <a href="{{ route('user.edit', $user->id) }}"
        class="w-full px-4 py-2 text-sm border rounded rounded-r-none btn border-gray-50 hover:bg-gray-50/50 dark:border-zinc-600 dark:hover:bg-zinc-600 dark:text-gray-100">
        Profile
    </a>
    <form wire:submit.prevent="confirmSweetAlertDelete('{{ $user->id }}')">
        @method('DELETE')
        @csrf
        <button type="submit"
            class="w-full px-4 py-2 text-sm border rounded rounded-r-none btn border-gray-50 hover:bg-gray-50/50 dark:border-zinc-600 dark:hover:bg-zinc-600 dark:text-gray-100">
            Remove
        </button>
    </form>
</div> --}}

<div class="btn-group">
    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">Profile</a>
    <button wire:click="deleteRecord({{ $user->id }})">Deletexxx</button>
    {{-- <form wire:submit.prevent="confirmSweetAlertDelete('{{ $user->id }}')">
        @method('DELETE')
        @csrf
        <button type="submit" class="btn btn-danger">Remove</button>
    </form> --}}
</div>