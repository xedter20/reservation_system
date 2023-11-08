<?php

namespace App\Http\Livewire;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class FaqTable extends LivewireTableComponent
{
    protected $model = Faq::class;
    public bool $showButtonOnHeader = true;
    public string $buttonComponent = 'fronts.faqs.components.add_button';
    protected $listeners = ['refresh' => '$refresh','resetPage'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('created_at', 'desc')
            ->setQueryStringStatus(false);
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return Faq::query();
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('messages.faq.question'), 'question')->view('fronts.faqs.components.question')
                ->sortable()->searchable(),
            Column::make(__('messages.faq.answer'), 'answer')->view('fronts.faqs.components.answer')
                ->sortable()->searchable(),
            Column::make(__('messages.common.action') ,'id')->view('fronts.faqs.components.action'),
        ];
    }
}
