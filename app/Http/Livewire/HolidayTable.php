<?php

namespace App\Http\Livewire;

use App\Models\Doctor;
use App\Models\DoctorHoliday;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class HolidayTable extends LivewireTableComponent
{
    protected $model = DoctorHoliday::class;
    public bool $showButtonOnHeader = true;
    public string $buttonComponent = 'holiday.add_button';
    public bool $showFilterOnHeader = true;
    public array $FilterComponent = ['holiday.components.filter', []];
    protected $listeners = ['refresh' => '$refresh', 'resetPage', 'changeDateFilter'];
    public string $dateFilter = '';

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('name')) {
                return [
                    'class' => 'w-75',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.web.reason'), 'name')->view('holiday.components.reason')
                ->sortable()->searchable(),
            Column::make(__('messages.appointment.date'), 'date')->view('holiday.components.date')
                ->sortable(),
            Column::make(__('messages.common.action'), 'id')->view('holiday.components.action'),
        ];
    }

    public function changeDateFilter($date)
    {
        $this->dateFilter = $date;
        $this->setBuilder($this->builder());
    }

    public function builder(): Builder
    {
        $doctor = Doctor::whereUserId(getLogInUserId())->first('id');
        $doctorId = $doctor['id'];
        $query = DoctorHoliday::whereDoctorId($doctorId);

        if ($this->dateFilter != '' && $this->dateFilter != getWeekDate()) {
            $timeEntryDate = explode(' - ', $this->dateFilter);
            $startDate = Carbon::parse($timeEntryDate[0])->format('Y-m-d');
            $endDate = Carbon::parse($timeEntryDate[1])->format('Y-m-d');
            $query->whereBetween('date', [$startDate, $endDate]);
        } else {
            $timeEntryDate = explode(' - ', getWeekDate());
            $startDate = Carbon::parse($timeEntryDate[0])->format('Y-m-d');
            $endDate = Carbon::parse($timeEntryDate[1])->format('Y-m-d');
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query;
    }
}
