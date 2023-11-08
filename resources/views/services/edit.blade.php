@extends('layouts.app')
@section('title')
    {{__('messages.service.edit_service')}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <h1>@yield('title')</h1>
            <a class="btn btn-outline-primary float-end"
               href="{{ route('services.index') }}">{{ __('messages.common.back') }}</a>
        </div>

        <div class="col-12">
            @include('layouts.errors')
        </div>
        <div class="card">
            <div class="card-body">
                {{ Form::model($service, ['route' => ['services.update', $service->id], 'method' => 'PUT', 'files' => true]) }}
                    @include('services.fields')
                    {{ Form::close() }}
            </div>
        </div>
        @include('services.create-modal')
    </div>
@endsection
