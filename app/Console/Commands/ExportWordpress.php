<?php

namespace App\Console\Commands;

use App\Events\FixtureStatusUpdate;
use App\Models\Fixtures;
use App\Models\Leagues;
use App\Models\TextGenerator;
use App\Services\WordpressService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use function Laravel\Prompts\error;
use Illuminate\Support\Facades\Log;

class ExportWordpress extends Command
{
    protected string $countryName = '';
    protected string $leagueName = '';
    protected string $round = '';
    protected string $homeTeam = '';
    protected string $awayTeam = '';

    public function __construct(
        protected WordpressService $wordpressService,
        protected TextGenerator $generatorModel
    ){
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'top:export-wordpress {fixtureId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export generated template as a post to WordPress';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fixtureId = $this->argument('fixtureId');
        $this->registerCustomTableStyle();
        $headers = ['ID', 'Match', 'Updated At', 'WordPress'];

        try {
            switch ($fixtureId) {
                case 'all':
                    // Get all 'pending' generations and post to WordPress
                    $generations = TextGenerator::all()->where('status', '=', TextGenerator::STATUS_PENDING);

                    $tableData = [];
                    foreach ($generations as $generation) {
                        $fixture = Fixtures::all()->where('fixture_id', '=', $generation->fixture_id)->first();
                        $this->init($fixture);
                        $posted = $this->processGeneration($fixture);
                        $tableData[] = $posted['report'];
                    }

                    $this->renderTable($headers, $tableData);
                    break;
                case is_numeric($fixtureId):
                    // Get specific 'pending' generated text for fixture and post it to WordPress
                    $fixture = Fixtures::all()->where('fixture_id', $fixtureId)->first();
                    $this->init($fixture);
                    $posted = $this->wordpressService->processGeneration($fixture);

                    if (isset($posted['report'])) {
                        $this->renderTable($headers, $posted['report']);
                    } else {
                        throw new \Exception(' no report found from WordPress');
                    }

                default:
                    Log::channel('wordpress')->error("Invalid input");
            }
        } catch (\Throwable $exception) {
            FixtureStatusUpdate::dispatch($fixture, 'FixturePublished', 11);
            Log::channel('wordpress')->error("$this->countryName | $this->leagueName | $this->round: $this->homeTeam - $this->awayTeam - #" . $fixtureId . $exception->getMessage());
            error("$this->countryName | $this->leagueName | $this->round: $this->homeTeam - $this->awayTeam - #" . $fixtureId . ' >>> ' . $exception->getMessage());
        }
    }

    /**
     * @param Fixtures $fixture
     * @return void
     * @throws \Exception
     */
    protected function init(Fixtures $fixture): void
    {
        $league = Leagues::all()->where('api_football_id', $fixture->league_id)->first();
        $this->countryName = $league->country->name;
        $this->leagueName = $league->name;
        $this->round = $fixture->round;

        $fixtureData = json_decode($fixture->fixtures, true);
        if (empty($fixtureData)) {
            throw new \Exception(' missing fixture data');
        }
        $this->homeTeam = $fixtureData[0]['teams']['home']['name'];
        $this->awayTeam = $fixtureData[0]['teams']['away']['name'];
    }

    /**
     * @param $headers
     * @param $data
     * @return void
     */
    public function renderTable($headers, $data): void
    {
        $table = new Table($this->output);
        $table->setHeaders($headers)
            ->setRows([
                [
                    'ID' => $data['ID'],
                    'Match' => $data['Match'],
                    'Updated At' => $data['Updated At'],
                    'WordPress' => $data['WordPress']
                ]
            ])
            ->setStyle('tipsOddsPredictions');

        $table->setHeaderTitle("$this->countryName | $this->leagueName | {$this->round}");
        $table->render();
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
