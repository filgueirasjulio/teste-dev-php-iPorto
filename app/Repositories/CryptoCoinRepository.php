<?php

namespace App\Repositories;

use App\Models\CryptoCoin;

class CryptoCoinRepository extends BaseRepository
{
    protected $entity;

    /**
     * construtor.
     *
     * @param CryptoCoin $model
     *
     * @return void
     */
    public function __construct(CryptoCoin $model)
    {
        parent::__construct($model);
    }

    /**
     * create.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create(
            [
                'symbol' => $data['symbol'],
                'price' => $data['price'],
                'time' => $data['time'],
            ]
        );
    }

    /**
     * calculateAveragePrice.
     *
     * @param string $symbol
     *
     * @return mixed
     */
    public function calculateAveragePrice($symbol)
    {
        $averagePrice = $this->where('symbol', $symbol)
            ->take(100)
            ->avg('price');

        return $averagePrice;
    }

    /**
     * isCurrentPriceLowerThanAverage.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function isCurrentPriceLowerThanAverage(array $data)
    {
        $price = $data['price'];
        $symbol = $data['symbol'];

        $averagePrice = $this->calculateAveragePrice($symbol);

        if ($price < $averagePrice * 1) {
            return true;
        } else {
            return false;
        }
    }
}
