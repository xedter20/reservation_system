<?php

namespace App\Http\Livewire;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DoctorAppointmentTable extends LivewireTableComponent
{
    public $doctorId;
    protected $model = Appointment::class;
    public bool $showFilterOnHeader = true;
    public array $FilterComponent = ['doctor_appointment.components.filter', Appointment::STATUS];
    protected $listeners = ['refresh' => '$refresh', 'resetPage', 'changeStatusFilter', 'changeDateFilter'];
    public int $statusFilter = Appointment::BOOKED;
    public string $dateFilter = '';

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        $query = Appointment::with(['patient.user'])->where('doctor_id', '=',
            $this->doctorId)->select('appointments.*');

        $query->when($this->statusFilter != '' && $this->statusFilter != Appointment::ALL_STATUS,
            function (Builder $q) {
                if ($this->statusFilter != Appointment::ALL) {
                    $q->where('appointments.status', '=', $this->statusFilter);
                }
            });

        if ($this->dateFilter != '' && $this->dateFilter != getWeekDate()) {
            $timeEntryDate = explode(' - ', $this->dateFilter);
            $startDate = Carbon::parse($timeEntryDate[0])->format('Y-m-d');
            $endDate = Carbon::parse($timeEntryDate[1])->format('Y-m-d');
            $query->whereBetween('appointments.date', [$startDate, $endDate]);
        } else {
            $timeEntryDate = explode(' - ', getWeekDate());
            $startDate = Carbon::parse($timeEntryDate[0])->format('Y-m-d');
            $endDate = Carbon::parse($timeEntryDate[1])->format('Y-m-d');
            $query->whereBetween('appointments.date', [$startDate, $endDate]);
        }

        return $query;
    }

    /**
     * @param $status
     */
    public function changeStatusFilter($status)
    {
        if ($status == null){
            $status = 1;
        }
        $this->statusFilter = $status;
        $this->setBuilder($this->builder());
    }

    /**
     * @param $date
     */
    public function changeDateFilter($date)
    {
        $this->dateFilter = $date;
        $this->setBuilder($this->builder());
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('messages.appointment.patient'),
                'patient.user.first_name')->view('doctor_appointment.components.patient')
                ->sortable()
                ->searchable(
                    function (Builder $query, $direction) {
                        return $query->whereHas('patient.user', function (Builder $q) use ($direction) {
                            $q->whereRaw("TRIM(CONCAT(first_name,' ',last_name,' ')) like '%{$direction}%'");
                        });
                    }
                ),
            Column::make(__('messages.appointment.appointment_at'),
                'date')->view('doctor_appointment.components.appointment_at')
                ->sortable()->searchable(),
            Column::make(__('messages.appointment.status'), 'id')
                ->format(function ($value, $row) {
                    return view('appointments.components.status')
                        ->with([
                            'row'      => $row,
                            'book'     => Appointment::BOOKED,
                            'checkIn'  => Appointment::CHECK_IN,
                            'checkOut' => Appointment::CHECK_OUT,
                            'cancel'   => Appointment::CANCELLED,
                        ]);
                }),
            Column::make(__('messages.common.action'), 'id')->view('doctor_appointment.components.action'),
        ];
    }
}
