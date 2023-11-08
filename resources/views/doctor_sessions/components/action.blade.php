<div class="d-flex justify-content-center">
    <a href="{{ route('doctor-sessions.edit', $row->id) }}" title="{{ __('messages.common.edit') }}"
       data-bs-toggle="tooltip"
       data-bs-original-title="{{ __('messages.common.edit') }}"
       class="btn px-1 text-primary fs-3 user-edit-btn" data-id="{{$row->id}}">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <a href="javascript:void(0)" data-id="{{ $row->id }}" title="{{ __('messages.common.delete') }}"
       data-bs-toggle="tooltip"
       data-bs-original-title="{{ __('messages.common.delete') }}"
       class="btn px-1 text-danger fs-3 doctor-session-delete-btn">
        <i class="fa-solid fa-trash"></i>
    </a>
</div>
