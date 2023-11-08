<div class="row">
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('name', __('messages.front_patient_testimonial.name').':', ['class' => 'form-label required']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('messages.front_patient_testimonial.name'), 'required']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('designation', __('messages.front_patient_testimonial.designation').':', ['class' => 'form-label required']) }}
            {{ Form::text('designation', null, ['class' => 'form-control', 'placeholder' => __('messages.front_patient_testimonial.designation'), 'required']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('short_description', __('messages.front_patient_testimonial.short_description').':', ['class' => 'form-label required']) }}
            {{ Form::textarea('short_description', null, ['class' => 'form-control', 'placeholder' => __('messages.front_patient_testimonial.short_description'), 'required', 'id' => 'shortDescription', 'rows'=> 5, 'maxlength' => '111']) }}
        </div>
    </div>
    <div class="col-lg-6 mb-7">
        <div class="mb-3" io-image-input="true">
            <label for="exampleInputImage" class="form-label required">Profile:</label>
            <div class="d-block">
                <div class="image-picker">
                    <div class="image previewImage" id="exampleInputImage" style="background-image: url({{ asset('web/media/avatars/male.png') }})">
                    </div>
                    <span class="picker-edit rounded-circle text-gray-500 fs-small"
                          data-bs-toggle="tooltip"
                          data-placement="top" data-bs-original-title="{{ __('messages.common.change_image') }}">
                        <label> 
                            <i class="fa-solid fa-pen" id="profileImageIcon"></i> 
                            <input type="file" id="profile_image" name="profile" class="image-upload d-none profile-validation" accept="image/*" /> 
                        </label> 
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex">
        {{ Form::submit(__('messages.common.save'),['class' => 'btn btn-primary me-2']) }}
        <a href="{{ route('front-patient-testimonials.index') }}" type="reset"
           class="btn btn-secondary">{{__('messages.common.discard')}}</a>
    </div>
</div>

