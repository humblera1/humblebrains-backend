<?php

namespace App\Services\Api;

class PointsService
{
    const MAX_POINTS_PER_ANSWER = 500;

    /**
     * Calculates the average number of correct answers per game level.
     *
     * @param array $levels
     * @return float
     */
    public function calculateAdjustmentFactor(array $levels): float
    {
        $answersAmountPerLevel = [];

        foreach ($levels as $level) {
            if (!array_key_exists('correct_answers_before_finish', $level)) {
                throw new \InvalidArgumentException("Level data must have 'correct_answers_before_finish' key");
            }

            if (!array_key_exists('successful_rounds_before_promotion', $level)) {
                throw new \InvalidArgumentException("Level data must have 'successful_rounds_before_promotion' key");
            }

            $answersAmountPerLevel[] = $level['correct_answers_before_finish'] * $level['successful_rounds_before_promotion'];
        }

        return round(array_sum($answersAmountPerLevel) / count($levels), 2);

    }

    /**
     * Calculates the number of points for correct answer on each level.
     * Returns a two-dimensional array:
     *  <pre>
     *  Array
     *  (
     *      [level_number] => points_per_answer_value
     *  )
     *  </pre>
     *
     * @param array $levels
     * @return array
     */
    function calculatePointsPerAnswer(array $levels): array
    {
        $totalLevels = count($levels);
        $adjustmentFactor = $this->calculateAdjustmentFactor($levels);

        $levelPoints = [];
        for ($n = 1; $n <= $totalLevels; $n++) {
            $pointsForLevel = ($n / $totalLevels) * self::MAX_POINTS_PER_ANSWER;
            $adjustedPointsPerAnswer = $pointsForLevel / ($adjustmentFactor / 10);

            $levelPoints[$n] = (int) $adjustedPointsPerAnswer;
        }

        return $levelPoints;
    }
}
