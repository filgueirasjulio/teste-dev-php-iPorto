<?php

namespace App\Console\Commands;

use App\Repositories\CryptoCoinRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SaveBidPriceOnDataBaseCommand extends Command
{
    public $repository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'c:saveBidPriceOnDataBase {symbol?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Saves price data in the database based on the entered cryptocoin';

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
        $info = null;

        $response = Http::get('https://testnet.binancefuture.com/fapi/v1/ticker/price', [
            'symbol' => $symbol ? $symbol : null,
        ]);

        if ($symbol) {
            $cryptoCoin = $response->json();

            $result = $this->repository->create($cryptoCoin);

            $info = $result;
        } else {
            $cryptoCoins = $response->json();

            $coins_list = [];

            foreach ($cryptoCoins as $coin) {
                $result = $this->repository->create($coin);
                $result ? $coins_list[] = $coin : null;
            }

            $info = json_encode($coins_list);
        }

        $this->info($info);
    }
}
