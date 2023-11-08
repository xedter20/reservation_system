<?php

namespace App\Http\Livewire;

use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CityTable extends LivewireTableComponent
{
    protected $model = City::class;
    public bool $showButtonOnHeader = true;
    public string $buttonComponent = 'cities.components.add_button';
    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

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
        return City::with('state')->select('cities.*');
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('messages.common.name'), 'name')->view('cities.components.name')
                ->sortable()->searchable(),
            Column::make(__('messages.city.state'), 'state.name')->view('cities.components.state')
                ->sortable()->searchable(),
            Column::make(__('messages.common.action') ,'id')->view('cities.components.action'),
        ];
    }
}
