<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "//www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/img/hms-saas-favicon.ico') }}" type="image/png">
    <title>Prescription Report</title>
    <link href="{{ asset('assets/css/prescription-pdf.css') }}" rel="stylesheet" type="text/css"/>
</head>
<body>
<div class="row">
    <div class="col-md-4 col-sm-6 co-12">
        <div class="image mb-7">
            <img src="{{ $data['logo'] }}" alt="user" class="img-fluid max-width-180">
        </div>
        <h3>
            {{ !empty($prescription['prescription']->doctor->doctorUser->full_name) ? $prescription['prescription']->doctor->doctorUser->full_name : '' }}
        </h3>
        <h4 class="fs-5 text-gray-600 fw-light mb-0">
            {{ !empty($prescription['prescription']->doctor->specialist) ? $prescription['prescription']->doctor->specialist : '' }}
        </h4>
    </div>
    <div class="col-md-4 col-sm-6 co-12 mt-sm-0 mt-5 header-right">
        <div class="d-flex flex-row">
            <label for="name" class="pb-2 fs-5 text-gray-600 me-1">Patient Name:</label>
            <span class="fs-5 text-gray-800">
                {{ !empty($prescription['prescription']->patient->patientUser->full_name) ? $prescription['prescription']->patient->patientUser->full_name : '' }}
            </span>
        </div>
        <div class="d-flex flex-row">
            <label for="name" class="pb-2 fs-5 text-gray-600 me-1">Date:</label>
            <span class="fs-5 text-gray-800">
                {{ !empty(\Carbon\Carbon::parse($prescription['prescription']->created_at)->isoFormat('DD/MM/Y')) ? \Carbon\Carbon::parse($prescription['prescription']->created_at)->isoFormat('DD/MM/Y') : ''}}
            </span>
        </div>
        <div class="d-flex flex-row">
            <label for="name" class="pb-2 fs-5 text-gray-600 me-1">Age:</label>
            <span class="fs-5 text-gray-800">
                @if($prescription['prescription']->patient->user->dob)
                    {{ \Carbon\Carbon::parse($prescription['prescription']->patient->user->dob)->diff(\Carbon\Carbon::now())->y }}
                    Years
                @else
                    N/A
                @endif
            </span>
        </div>
    </div>
    <div class="col-md-4 co-12 mt-md-0 mt-5">
        @if(empty($prescription['prescription']->doctor->address->address1) && empty($prescription['prescription']->doctor->address->address2) && empty($prescription['prescription']->doctor->address->city))
            {{ __('messages.common.n/a') }}
        @else
            {{ !empty($prescription['prescription']->doctor->address->address1) ? $prescription['prescription']->doctor->address->address1 : '' }}
            {{ !empty($prescription['prescription']->doctor->address->address2) ? !empty($prescription['prescription']->doctor->address->address1) ? ',' : '' : '' }}
            {{ empty($prescription['prescription']->doctor->address->address1) || !empty($prescription['prescription']->doctor->address->address2)  ? !empty($prescription['prescription']->doctor->address->address2) ? $prescription['prescription']->doctor->address->address2  : '' : ''  }}
            {{ !empty($prescription['prescription']->doctor->address->city) ? ',' : '' }}
            @if(!empty($prescription['prescription']->doctor->address->city))
                <br>
            @endif
            {{ !empty($prescription['prescription']->doctor->address->city) ? $prescription['prescription']->doctor->address->city : '' }}
            {{ !empty($prescription['prescription']->doctor->address->zip) ? ',' : '' }}
            @if($prescription['prescription']->doctor->address->zip)
                <br>
            @endif
            {{ !empty($prescription['prescription']->doctor->address->zip) ? $prescription['prescription']->doctor->address->zip : '' }}
            <p class="text-gray-600 mb-3">
                {{ !empty($prescription['prescription']->doctor->user->phone) ? $prescription['prescription']->doctor->user->phone : '' }}
            </p>
            <p class="text-gray-600 mb-3">
                {{ !empty($prescription['prescription']->doctor->user->email) ? $prescription['prescription']->doctor->user->email : '' }}
            </p>
        @endif
    </div>
    <div class="col-12 px-0">
        <hr class="line my-lg-10 mb-6 mt-4">
    </div>
    <div class="col-md-4 col-sm-6 co-12">
        <h3>Problem:</h3>
        @if($prescription['prescription']->problem_description != null)
            <p class="text-gray-600 mb-2 fs-4">{{ $prescription['prescription']->problem_description }}</p>
        @else
            N/A
        @endif
    </div>
    <div class="col-md-4 col-sm-6 co-12 mt-sm-0 mt-5">
        <h3>Test:</h3>
        @if($prescription['prescription']->test != null)
            <p class="text-gray-600 mb-2 fs-4">{{ $prescription['prescription']->test }}</p>
        @else
            N/A
        @endif
    </div>
    <div class="col-md-4 col-sm-6 co-12 mt-md-0 mt-5">
        <h3>Advice:</h3>
        @if($prescription['prescription']->advice != null)
            <p class="text-gray-600  mb-2 fs-4">{{ $prescription['prescription']->advice }}</p>
        @else
            N/A
        @endif
    </div>
    <div class="col-12 mt-6">
        <h3>Rx:</h3>
        <table class="items-table">
            <thead>
            <tr>
                <th scope="col">MEDICINE NAME</th>
                <th scope="col">DOSAGE</th>
                <th scope="col">DURATION</th>
            </tr>
            </thead>
            <tbody>
            @if(empty($medicines))
                N/A
            @else
                @foreach($prescription['prescription']->getMedicine as $medicine)
                    @foreach($medicines as $medi)
                        @foreach($medi as $md)
                            <tr>
                                <td class="py-4 border-bottom-0">{{ $md->name }}</td>
                                <td class="py-4 border-bottom-0">
                                    {{ $medicine->dosage }}
                                    @if($medicine->time == 0)
                                        After Meal
                                    @else
                                        Before Meal
                                    @endif
                                </td>
                                <td class="py-4 border-bottom-0">{{ $medicine->day }} Day</td>
                            </tr>
                        @endforeach
                        @break
                    @endforeach
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <br>
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between flex-wrap mt-5">
            <div class="mt-3">
                <h4>{{ !empty($prescription['prescription']->doctor->doctorUser->full_name) ? $prescription['prescription']->doctor->doctorUser->full_name : '' }}</h4>
                <h5 class="text-gray-600 fw-light mb-0">{{ !empty($prescription['prescription']->doctor->specialist) ? $prescription['prescription']->doctor->specialist : '' }}</h5>
            </div>
        </div>
    </div>
</div>
</body>
