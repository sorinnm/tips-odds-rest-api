<?php

namespace App\Console\Commands;

use App\Events\FixtureStatusUpdate;
use App\Events\GenerationCheck;
use App\Models\Countries;
use App\Models\Fixtures;
use App\Models\Leagues;
use App\Models\Seasons;
use App\Models\TextGenerator;
use App\Services\ChatGPTService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\error;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;

class GenerateFixtureText extends Command
{
    protected string $countryName = '';
    protected string $leagueName = '';
    protected string $round = '';
    protected string $homeTeam = '';
    protected string $awayTeam = '';

    /**
     * @param ChatGPTService $chatGPTService
     * @param Fixtures $fixtures
     * @param Leagues $leagues
     * @param Seasons $seasons
     * @param Countries $countries
     * @param TextGenerator $textGenerator
     */
    public function __construct(
        protected ChatGPTService $chatGPTService,
        protected Fixtures $fixtures,
        protected Leagues $leagues,
        protected Seasons $seasons,
        protected Countries $countries,
        protected TextGenerator $textGenerator
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'top:chatgpt:generate {fixtureId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate text for fixtures using ChatGPT';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $fixtureId = $this->argument('fixtureId');
        $this->registerCustomTableStyle();
        $headers = ['ID', 'Match', 'Updated At', 'ChatGPT'];

        try {
            switch ($fixtureId) {
                case 'all':
                    // Get all 'pending' fixtures and generate AI text
                    $fixtures = Fixtures::all()->where('status', '=', Fixtures::STATUS_PENDING);

                    $tableData = [];
                    foreach ($fixtures as $fixture) {
                        $this->init($fixture);
                        $fixtureId = $fixture->fixture_id;
                        $tableData[] = $this->processFixture($fixture);
                    }

                    $this->renderTable($headers, $tableData);

                    break;
                case is_numeric($fixtureId):
                    // Get specific 'pending' fixture and generate AI text

                    $fixture = Fixtures::all()->where('fixture_id', $fixtureId)->first();
                    $this->init($fixture);
                    $generatedData = $this->processFixture($fixture);
                    $this->renderTable($headers, $generatedData);
                    break;
                default:
                    // Invalid input
                    Log::channel('chatgpt')->error('Invalid input');
            }
        } catch (\Throwable $exception) {
            Log::channel('chatgpt')->error("$this->countryName | $this->leagueName | $this->round: $this->homeTeam - $this->awayTeam - #" . $fixtureId . $exception->getMessage());
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
        $league = Leagues::all()->where('api_football_id', $fixture->league->api_football_id)->first();
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
     * @param Fixtures $fixture
     * @return array
     * @throws \Exception
     */
    public function processFixture(Fixtures $fixture): array
    {
        if ($fixture->status !== Fixtures::STATUS_PENDING) {
            Log::channel('chatgpt')->warning($fixture->fixture_id . ' is not pending generation >>> Status: ' . $fixture->status);
            throw new \Exception(' is not pending generation >>> Status: ' . $fixture->status);
        }

        $generatedData = $this->chatGPTService->generateFixtureData($fixture);
        $generatedData['Match'] = "$this->homeTeam - $this->awayTeam";

        return $generatedData;
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
                ['ID' => $data['ID'],
                'Match' => $data['Match'],
                'Updated At' => $data['Updated At'],
                'ChatGPT' => $data['ChatGPT']]
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
