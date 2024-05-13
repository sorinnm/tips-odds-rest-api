<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TextGenerator extends Model
{
    use HasFactory;

    protected $table = 'generations';
    protected $fillable = ['fixture_id', 'generation', 'status', 'created_at', 'updated_at'];

    const STATUS_COMPLETE = 'complete';
    const STATUS_PENDING = 'pending';
    const STATUS_RUNNING = 'running';
    const STATUS_RETRY = 'retry';
    const STATUS_ERROR = 'error';

    /**
     * @param array $data
     * @return bool
     */
    public function store(array $data): bool
    {
        // Before creating a new generation, try to find an existing one
        $generations = TextGenerator::all();
        $generation = $generations->firstWhere('fixture_id', $data['fixture_id']);

        if (empty($fixture)) {
            $generation = new TextGenerator();
        }

        foreach ($data as $column => $value) {
            $generation->$column = $value;
        }

        return $generation->save();
    }

    /**
     * @param int $fixtureId
     * @return string
     */
    public function getGeneration(int $fixtureId): string
    {
        $generation = TextGenerator::all()
            ->where('fixture_id', $fixtureId)
            ->where('status', '=', self::STATUS_PENDING)->first();

        return trim(trim($generation->generation), '```json');
    }
}
