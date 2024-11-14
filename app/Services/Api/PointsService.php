<?php

namespace App\Services\Api;

class PointsService
{
    const MAX_POINTS_PER_ANSWER = 500;

    public function square($totalPoints, $totalLevels)
    {
        $levelPoints = [];

        $sum = array_sum(range(1, $totalLevels));

        foreach (range(1, $totalLevels) as $level) {
            $points = ($level ** 2 / $sum) * $totalPoints;

            $levelPoints[$level] = (int) $points;
        }

        return $levelPoints;
    }

    public function log($totalPoints, $totalLevels)
    {
        $sumOfLogs = 0;
        for ($i = 1; $i <= $totalLevels; $i++) {
            $sumOfLogs += log($i + 1);
        }

        $levelPoints = [];
        for ($n = 1; $n <= $totalLevels; $n++) {
            $points = (log($n + 1) / $sumOfLogs) * $totalPoints;

            $levelPoints[$n] = (int) $points;
        }

        return $levelPoints;
    }

    public function linear($totalPoints, $totalLevels)
    {
        $sumOfLevels = array_sum(range(1, $totalLevels));

        // Базовое количество очков для каждого уровня
        $basePoints = 1;

        // Расчет очков для каждого уровня
        $levelPoints = [];
        for ($n = 1; $n <= $totalLevels; $n++) {
            // Добавляем базовое количество очков и пропорциональную часть
            $points = $basePoints + (($n / $sumOfLevels) * ($totalPoints - $totalLevels * $basePoints));
            $levelPoints[$n] = (int) $points;
        }

        return $levelPoints;
    }

    public function linearWithLog($totalPoints, $totalLevels)
    {
        // Сумма номеров уровней
        $sumOfLevels = array_sum(range(1, $totalLevels));

        // Базовое количество очков для каждого уровня
        $basePoints = 1;

        // Коэффициент для логарифмического компонента
//        $logWeight = 0.05 + 0.5 * ($totalLevels / 10); // Уменьшает влияние логарифмического компонента
        $logWeight = 0.075;

        // Расчет очков для каждого уровня
        $levelPoints = [];
        for ($n = 1; $n <= $totalLevels; $n++) {
            // Линейная часть
            $linearComponent = $n / $sumOfLevels;

            // Логарифмическая часть с уменьшенным весом
            $logComponent = $logWeight * (log($n + 1) / log($totalLevels + 1));
//            $logComponent = (log($n + 1) / log($totalLevels + 1));

            // Общие очки для уровня с учетом обоих компонентов
            $pointsForLevel = $basePoints + (($linearComponent + $logComponent) / (1 + $logWeight)) * ($totalPoints - $totalLevels * $basePoints);

            $levelPoints[$n] = (int) $pointsForLevel;
        }

        return $levelPoints;
    }

    function calculatePointsWithFixedMax($maxPointsPerAnswer, $totalLevels) {
        // Расчет очков для каждого уровня
        $levelPoints = [];
        for ($n = 1; $n <= $totalLevels; $n++) {
            // Линейное распределение очков от 1 до maxPointsPerAnswer
            $pointsPerAnswer = ($n / $totalLevels) * $maxPointsPerAnswer;
            $levelPoints[$n] = (int) $pointsPerAnswer;
        }

        return $levelPoints;
    }

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
