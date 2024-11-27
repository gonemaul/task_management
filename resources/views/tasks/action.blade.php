<div class="dropdown">
    <button class="btn action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#detail"
                data-item="{{ $item }}" id="detail-{{ $item->id }}">Detail</a></li>
        <li><a class="dropdown-item" href="{{ route('tasks.edit', $item->id) }}">Edit</a></li>
        @if ($item->status != 'completed')
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changeStatus"
                    id="change_task-{{ $item->id }}" data-item="{{ $item }}">Ubah
                    Status</a></li>
            <li><a class="dropdown-item" href="{{ route('markAsCompleted', $item->id) }}">Tandai Selesai</a></li>
        @endif
        <li><a class="dropdown-item" href="#" id="delete-{{ $item->id }}"
                data-id="{{ $item->id }}">Delete</a></li>
    </ul>
</div>
