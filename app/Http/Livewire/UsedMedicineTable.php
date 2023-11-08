<?php

namespace App\Http\Livewire;

use App\Models\MedicineBill;
use App\Models\SaleMedicine;
use App\Models\UsedMedicine;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;


class UsedMedicineTable extends LivewireTableComponent
{
    public bool $showFilterOnHeader = false;

    public bool $showButtonOnHeader = false;

    protected $model = MedicineBill::class;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('sale_medicines.created_at', 'desc')
            ->setQueryStringStatus(false);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "medicine_bill_id")
                ->sortable()->hideIf(1),
            Column::make(__('messages.medicines'), "medicine_id")
                ->sortable()->searchable(
                    function (Builder $query, $direction) {
                        return $query->whereHas('medicine', function (Builder $q) use ($direction) {
                            $q->whereRaw("name like '%{$direction}%'");
                        });
                    }
                )->view('used-medicine.columns.medicine'),
            Column::make(__('messages.used_medicine.used_quantity'), "sale_quantity")
            ->sortable()->searchable()->view('used-medicine.columns.quantity'),
            Column::make("Model id", "medicineBill.model_id")
                ->sortable()->hideIf(1),
            Column::make(__('messages.used_medicine.used_at'), "medicineBill.model_type")
            ->sortable()->searchable()->view('used-medicine.columns.used_at'),
            Column::make(__('messages.appointment.date'), "created_at")
            ->sortable()->searchable()->view('used-medicine.columns.date'),

        ];
    }

    public function builder(): Builder
    {
        return SaleMedicine::with(['medicineBill','medicine'])->whereHas('medicineBill',function(Builder $q){
            $q->where('payment_status',true);
        });
    }
}
