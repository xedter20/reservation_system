<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PatientVisitTable extends LivewireTableComponent
{
    protected $model = Visit::class;
    protected $listeners = ['resetPage'];

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
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('messages.visit.doctor'), 'doctor.user.first_name')->view('patient_visits.components.doctor')
                ->sortable()->searchable(
                    function (Builder $query, $direction) {
                        return $query->whereHas('doctor.user', function (Builder $q) use ($direction) {
                            $q->whereRaw("TRIM(CONCAT(first_name,' ',last_name,' ')) like '%{$direction}%'");
                        });
                    }
                ),
            Column::make(__('messages.visit.doctor'), 'doctor.user.email')
                ->hideIf('doctor.user.email')
                ->searchable(),
            Column::make(__('messages.visit.visit_date'), 'visit_date')->view('patient_visits.components.visit')
                    ->sortable(),
            Column::make(__('messages.common.action'),'id')->view('patient_visits.components.action'),
        ];
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return Visit::with('visitDoctor.user', 'visitDoctor.reviews')->where('patient_id', getLoginUser()->patient->id)
            ->select('visits.*');
    }
}
