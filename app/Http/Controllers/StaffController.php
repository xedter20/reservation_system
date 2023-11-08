<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\User;
use App\Repositories\StaffRepository;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class StaffController extends AppBaseController
{
    /** @var StaffRepository */
    private $staffRepository;

    public function __construct(StaffRepository $staffRepo)
    {
        $this->staffRepository = $staffRepo;
    }

    /**
     * Display a listing of the Staff.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('staffs.index');
    }

    /**
     * Show the form for creating a new Staff.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $roles = $this->staffRepository->getRole();

        return view('staffs.create', compact('roles'));
    }

    /**
     * Store a newly created Staff in storage.
     *
     * @param  CreateStaffRequest  $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(CreateStaffRequest $request)
    {
        $input = $request->all();
        $this->staffRepository->store($input);

        Flash::success(__('messages.flash.staff_create'));

        return redirect(route('staffs.index'));
    }

    /**
     * @param  User  $staff
     * @return Application|Factory|View
     */
    public function show(User $staff)
    {
        $staff = User::whereType(User::STAFF)->findOrFail($staff['id']);

        return view('staffs.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified Staff.
     *
     * @param  User  $staff
     * @return Application|Factory|View
     */
    public function edit(User $staff)
    {
        $roles = $this->staffRepository->getRole();

        return view('staffs.edit', compact('staff', 'roles'));
    }

    /**
     * Update the specified Staff in storage.
     *
     * @param  UpdateStaffRequest  $request
     * @param  User  $staff
     * @return Application|RedirectResponse|Redirector
     */
    public function update(UpdateStaffRequest $request, User $staff)
    {
        $this->staffRepository->update($request->all(), $staff->id);

        Flash::success(__('messages.flash.staff_update'));

        return redirect(route('staffs.index'));
    }

    /**
     * Remove the specified Staff from storage.
     *
     * @param  User  $staff
     * @return JsonResponse
     */
    public function destroy(User $staff)
    {
        $staff->delete();

        return $this->sendSuccess(__('messages.flash.staff_delete'));
    }
}
