<?php

namespace App\Console\Commands;

use App\Models\Countries;
use App\Models\Leagues;
use App\Models\Seasons;
use App\Models\Sports;
use App\Services\ApiFootballService;
use Illuminate\Console\Command;
use function Laravel\Prompts\error;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;

class ImportFixtures extends Command
{
    public function __construct(
        protected ApiFootballService $apiFootballService
    )
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'top:import-fixtures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import fixtures data from API-Football';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $countryName = $leagueName = '';
        $this->registerCustomTableStyle();;
        $sports = Sports::all();

        $headers = ['ID', 'Match', 'Updated At', 'API-Football'];
        $data['report'] = [];

        foreach ($sports as $sport) {
            $countries = Countries::all()->where('sport_id', $sport->id);

            foreach ($countries as $country) {
                $countryName = $country->name;
                $leagues = Leagues::all()->where('country_id', $country->id);

                foreach ($leagues as $league) {
                    $leagueName = $league->name;
                    $season = Seasons::all()
                        ->where('league_id', $league->id)
                        ->where('is_active', true)->first();

                    if ($season) {
                        try {
                            $this->apiFootballService->init($league->api_football_id, $season->name);
                            // Import fixtures data for the current round
                            $data = $this->apiFootballService->importFixtures(
                                $this->apiFootballService->leagueId,
                                $this->apiFootballService->seasonId,
                                $this->apiFootballService->round
                            );
                        } catch (\Throwable $exception) {
                            error("$countryName | $leagueName: " . $exception->getMessage());
                            continue;
                        }

                        $table = new Table($this->output);
                        $table->setHeaders($headers)
                            ->setRows($data['report'])
                            ->setStyle('tipsOddsPredictions');

                        $table->setHeaderTitle("$countryName | $leagueName | {$this->apiFootballService->round}");
                        $table->render();
                    }
                }
            }
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
