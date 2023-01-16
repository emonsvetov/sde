<?php

namespace App\Console\Commands;

use App\Services\SuitabilityScoreService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CalculateSuitableScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate {streetsFileName} {driversFileName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Suitable Score';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SuitabilityScoreService $suitabilityScoreService)
    {
        $streetsFileName = $this->argument('streetsFileName');
        $driversFileName = $this->argument('driversFileName');
        $result = (object)$suitabilityScoreService->calculate($streetsFileName, $driversFileName);

        echo PHP_EOL;
        echo "Suitability Score Total: {$result->total}" . PHP_EOL . PHP_EOL;
        echo "Result: " . PHP_EOL . PHP_EOL;
        foreach ($result->distributionResult as $item) {
            echo $item['street'] . ' : ' . $item['driver'] . " ({$item['score']})" . PHP_EOL;
        }
        echo PHP_EOL;
        echo PHP_EOL;
    }
}