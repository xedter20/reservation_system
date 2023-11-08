<?php

namespace App\Repositories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class CurrencyRepository
 *
 * @version August 26, 2021, 6:57 am UTC
 */
class CurrencyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'currency_name',
        'currency_icon',
        'currency_code',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Currency::class;
    }

    /**
     * @param $input
     * @return mixed
     */
    public function store($input)
    {
        $input['currency_code'] = strtoupper($input['currency_code']);
        $currency = Currency::create($input);

        return $currency;
    }

    /**
     * @param  array  $input
     * @param  int  $id
     * @return Builder|Currency
     */
    public function update($input, $id)
    {
        $input['currency_code'] = strtoupper($input['currency_code']);

        $currency = Currency::whereId($id);
        $currency->update([
            'currency_code' => $input['currency_code'],
            'currency_icon' => $input['currency_icon'],
            'currency_name' => $input['currency_name'],
        ]);

        return $currency;
    }
}
