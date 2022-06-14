<?php

namespace App\Console\Commands;

use App\Repositories\CryptoCoinRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckAvgBigPriceCommand extends Command
{
    public $repository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'c:checkAvgBigPrice {symbol?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the average price on the database and informs if the last fecthed price is less than 0.5% of the average price';

    /**
     * Construtor.
     *
     * @param CryptoCoinRepository $repository
     *
     * @return void
     */
    public function __construct(CryptoCoinRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $symbol = $this->argument('symbol');
        $result_list = [];

        $response = Http::get('https://testnet.binancefuture.com/fapi/v1/ticker/price', [
            'symbol' => $symbol ? $symbol : null,
        ]);

        if ($symbol) {
            $cryptoCoin = $response->json();

            $result = $this->repository->isCurrentPriceLowerThanAverage($cryptoCoin);

            $result ?
                $result_list[] = [$cryptoCoin['symbol'] => 'Current value for '.$cryptoCoin['symbol'].' is over 0.5% lower than average value!']
                : $result_list[] = [$cryptoCoin['symbol'] => 'Current value for '.$cryptoCoin['symbol'].' is ok'];
        } else {
            $cryptoCoins = $response->json();

            foreach ($cryptoCoins as $cryptoCoin) {
                $result = $this->repository->isCurrentPriceLowerThanAverage($cryptoCoin);

                $result ?
                    $result_list[] = [$cryptoCoin['symbol'] => 'Current value for '.$cryptoCoin['symbol'].' is over 0.5% lower than average value!']
                    : $result_list[] = [$cryptoCoin['symbol'] => 'Current value for '.$cryptoCoin['symbol'].' is ok'];
            }
        }

        $this->info(json_encode($result_list));
    }
}
