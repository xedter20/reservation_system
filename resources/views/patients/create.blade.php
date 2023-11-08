@extends('layouts.app')
@section('title')
    {{ __('messages.patient.add') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <h1>@yield('title')</h1>
            <a class="btn btn-outline-primary float-end"
               href="{{ route('patients.index') }}">{{ __('messages.common.back') }}</a>
        </div>

        <div class="col-12">
            @include('layouts.errors')
        </div>
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => 'patients.store','files' => 'true','id' => 'createPatientForm']) }}
                {{ Form::hidden('is_edit', false,['id' => 'patientIsEdit']) }}
                {{ Form::hidden('backgroundImg',asset('web/media/avatars/male.png'),['id' => 'patientBackgroundImg']) }}
                @include('patients.fields')
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
