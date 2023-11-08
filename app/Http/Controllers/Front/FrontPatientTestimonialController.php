<?php

namespace App\Http\Controllers\Front;

use App\DataTables\FrontPatientTestimonialDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateFrontPatientTestimonialRequest;
use App\Http\Requests\UpdateFrontPatientTestimonialRequest;
use App\Models\FrontPatientTestimonial;
use App\Repositories\FrontPatientTestimonialRepository;
use Datatables;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class FrontPatientTestimonialController extends AppBaseController
{
    /** @var FrontPatientTestimonialRepository */
    private $frontPatientTestimonialRepository;

    public function __construct(FrontPatientTestimonialRepository $frontPatientTestimonialRepo)
    {
        $this->frontPatientTestimonialRepository = $frontPatientTestimonialRepo;
    }

    /**
     * Display a listing of the FrontPatientTestimonial.
     *
     * @param  Request  $request
     * @return Application|Factory|View|Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of((new FrontPatientTestimonialDataTable())->get())->make(true);
        }

        return view('fronts.front_patient_testimonials.index');
    }

    /**
     * Show the form for creating a new FrontPatientTestimonial.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('fronts.front_patient_testimonials.create');
    }

    /**
     * Store a newly created FrontPatientTestimonial in storage.
     *
     * @param  CreateFrontPatientTestimonialRequest  $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(CreateFrontPatientTestimonialRequest $request)
    {
        $input = $request->all();
        $frontPatientTestimonial = $this->frontPatientTestimonialRepository->store($input);

        Flash::success(__('messages.flash.testimonial_creat'));

        return redirect(route('front-patient-testimonials.index'));
    }

    /**
     * Show the form for editing the specified FrontPatientTestimonial.
     *
     * @param  FrontPatientTestimonial  $frontPatientTestimonial
     * @return Application|Factory|View
     */
    public function edit(FrontPatientTestimonial $frontPatientTestimonial)
    {
        return view('fronts.front_patient_testimonials.edit', compact('frontPatientTestimonial'));
    }

    /**
     * Update the specified FrontPatientTestimonial in storage.
     *
     * @param  UpdateFrontPatientTestimonialRequest  $request
     * @param  FrontPatientTestimonial  $frontPatientTestimonial
     * @return Application|Redirector|RedirectResponse
     */
    public function update(
        UpdateFrontPatientTestimonialRequest $request,
        FrontPatientTestimonial $frontPatientTestimonial
    ) {
        $frontPatientTestimonial = $this->frontPatientTestimonialRepository->update($request->all(),
            $frontPatientTestimonial->id);

        Flash::success(__('messages.flash.testimonial_update'));

        return redirect(route('front-patient-testimonials.index'));
    }

    /**
     * Remove the specified FrontPatientTestimonial from storage.
     *
     * @param  FrontPatientTestimonial  $frontPatientTestimonial
     * @return Response
     */
    public function destroy(FrontPatientTestimonial $frontPatientTestimonial)
    {
        if ($frontPatientTestimonial->is_default) {
            return $this->sendError(__('messages.flash.testimonial_use'));
        }

        $frontPatientTestimonial->delete();

        return $this->sendSuccess(__('messages.flash.testimonial_delete'));
    }
}
