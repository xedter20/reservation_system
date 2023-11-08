<div class="dropdown d-flex align-items-center me-4 me-md-2" wire:ignore>
    <button class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" type="button"
            id="holidayFilterBtn"
            data-bs-auto-close="outside"
            data-bs-toggle="dropdown" aria-expanded="false">
        <p class="text-center">
            <i class='fas fa-filter'></i>
        </p>
    </button>
    <div class="dropdown-menu py-0" aria-labelledby="holidayFilterBtn">
        <div class="text-start border-bottom py-4 px-7">
            <h3 class="text-gray-900 mb-0">{{__('messages.common.filter_option')}}</h3>
        </div>
        <div class="p-5">
            <div class="mb-5">
                <label for="filterBtn" class="form-label">{{__('messages.appointment.date')}}:</label>
                <input type="text" class="form-control form-control-solid flatpickr-input" placeholder="Pick date range"
                       id="holidayDateFilter"/>
            </div>
            <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-secondary" id="holidayDateResetFilter">{{__('messages.common.reset')}}</button>
            </div>
        </div>
    </div>
</div>
