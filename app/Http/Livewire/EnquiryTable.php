<?php

namespace App\Http\Livewire;

use App\Models\Enquiry;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class EnquiryTable extends LivewireTableComponent
{
    protected $model = Enquiry::class;
    public bool $showFilterOnHeader = true;
    public array $FilterComponent = ['fronts.enquiries.components.filter', Enquiry::VIEW_NAME];
    protected $listeners = ['refresh' => '$refresh','resetPage','changeStatusFilter'];
    public string $statusFilter = '';

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center'
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
        $query = Enquiry::query();

        $query->when($this->statusFilter !== '' && $this->statusFilter != Enquiry::ALL,
            function (Builder $query) {
                return $query->where('view', $this->statusFilter);
            });

        return $query;
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('messages.common.name'), 'name')->view('fronts.enquiries.components.name')
                ->sortable()->searchable(),
            Column::make(__('messages.web.message'), 'message')->view('fronts.enquiries.components.message')
                ->sortable()->searchable(),
            Column::make(__('messages.web.status'), 'view')->view('fronts.enquiries.components.status')
                ->sortable()->searchable(),
            Column::make(__('messages.doctor.created_at'), 'created_at')->view('fronts.enquiries.components.created_at')
                ->sortable()->searchable(),
            Column::make(__('messages.common.action'), 'id')->view('fronts.enquiries.components.action'),
        ];
    }

    /**
     * @param $value
     */
    public function changeStatusFilter($value): void
    {
        $this->statusFilter = $value;
        $this->setBuilder($this->builder());
    }
}
