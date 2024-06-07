<?php

namespace App\Console\Commands;

use App\Models\Fixtures;
use App\Models\Leagues;
use App\Services\ApiFootballService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use function Laravel\Prompts\error;

class ReimportFixture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'top:reimport-fixture {fixtureId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-import existing fixture from API-Football';

    public function __construct(
        protected ApiFootballService $apiFootballService
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $fixtureId = $this->argument('fixtureId');
        $countryName = $leagueName = $homeTeam = $awayTeam = '';
        $this->registerCustomTableStyle();
        $headers = ['ID', 'Match', 'Updated At', 'API-Football'];

        if (!is_numeric($fixtureId)) {
            error('Invalid input');
            return;
        }

        try {
            $fixture = Fixtures::all()->firstWhere('fixture_id', $fixtureId);

            $fixtureData = json_decode($fixture->fixtures, true);
            if (empty($fixtureData)) {
                throw new \Exception(' missing fixture data');
            }
            $homeTeam = $fixtureData[0]['teams']['home']['name'];
            $awayTeam = $fixtureData[0]['teams']['away']['name'];
            $countryName = $fixture->league->country->name;
            $leagueName = $fixture->league->name;

            $data = $this->apiFootballService->reimportFixture($fixture);

            $table = new Table($this->output);
            $table->setHeaders($headers)
                ->setRows([$data['report']])
                ->setStyle('tipsOddsPredictions');

            $table->setHeaderTitle("$countryName | $leagueName | {$fixture->round}");
            $table->render();
        } catch (\Throwable $exception) {
            Log::channel('api-football')->error("$countryName | $leagueName | #$fixtureId - $homeTeam - $awayTeam could not be re-imported: " . $exception->getMessage());
            error("$countryName | $leagueName | #$fixtureId - $homeTeam - $awayTeam could not be imported: " . $exception->getMessage());
        }
    }

    /**
     * Define style for table
     *
     * @return void
     */
    private function registerCustomTableStyle(): void
    {
        $tableStyle = (new TableStyle())
            ->setHorizontalBorderChars('─')
            ->setVerticalBorderChars('│')
            ->setCrossingChars(' ', '┌', '─', '┐', '│', '┘', '─', '└', '│');
        Table::setStyleDefinition('tipsOddsPredictions', $tableStyle);
    }
}
