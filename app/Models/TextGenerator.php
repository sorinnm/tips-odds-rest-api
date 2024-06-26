<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TextGenerator extends Model
{
    use HasFactory;

    protected $table = 'generations';
    protected $fillable = ['fixture_id', 'generation', 'post_id', 'page_id', 'url', 'status', 'created_at', 'updated_at'];

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
        $generation = TextGenerator::all()->firstWhere('fixture_id', $data['fixture_id']);;

        if (empty($generation)) {
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

        $generation = trim($generation->generation, '```json');
        $generation = trim($generation, '```');
        $generation = trim($generation, '\n');
        return trim($generation);
    }
}
