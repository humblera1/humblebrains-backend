<?php

use App\Enums\Game\CategoryEnum;

return [
    'name' => 'matrix',
    'label' => [
        'ru' => 'Матрица',
        'en' => 'Matrix',
    ],
    'description' => [
        'ru' => 'Описание игры матрица',
        'en' => 'Matrix description',
    ],
    'max_level' => 30,
    'main_image' => 'games/matrix/main.png',
    'thumbnail_image' => 'games/matrix/thumbnail.png',
    'icon_image' => 'games/matrix/icon.png',
    'category' => CategoryEnum::Memory,
    'tutorial' => [
        'ru' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
    ],
    'levels' => [
        1 => [
            'time_to_contemplate' => 12,
            'time_to_answer' => 12,
            'correct_answers_before_finish' => 2,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 2,
            'square_side' => 3,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 1,
            'failed_rounds_before_demotion' => 1,
            'rotation_iterations' => 0,
            'has_order' => false,
        ],
        2 => [
            'time_to_contemplate' => 10,
            'time_to_answer' => 10,
            'correct_answers_before_finish' => 3,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 2,
            'square_side' => 3,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 2,
            'failed_rounds_before_demotion' => 3,
            'rotation_iterations' => 0,
            'has_order' => false,
        ],
        3 => [
            'time_to_contemplate' => 10,
            'time_to_answer' => 10,
            'correct_answers_before_finish' => 3,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 3,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 3,
            'failed_rounds_before_demotion' => 3,
            'rotation_iterations' => 0,
            'has_order' => false,
        ],
        4 => [
            'time_to_contemplate' => 8,
            'time_to_answer' => 8,
            'correct_answers_before_finish' => 3,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 3,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 3,
            'failed_rounds_before_demotion' => 3,
            'rotation_iterations' => 1,
            'has_order' => false,
        ],
        5 => [
            'time_to_contemplate' => 8,
            'time_to_answer' => 8,
            'correct_answers_before_finish' => 2,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 3,
            'colors_amount' => 2,
            'successful_rounds_before_promotion' => 4,
            'failed_rounds_before_demotion' => 3,
            'rotation_iterations' => 1,
            'has_order' => false,
        ],
        6 => [
            'time_to_contemplate' => 6,
            'time_to_answer' => 6,
            'correct_answers_before_finish' => 2,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 3,
            'colors_amount' => 2,
            'successful_rounds_before_promotion' => 5,
            'failed_rounds_before_demotion' => 4,
            'rotation_iterations' => 2,
            'has_order' => false,
        ],
        7 => [
            'time_to_contemplate' => 6,
            'time_to_answer' => 8,
            'correct_answers_before_finish' => 4,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 2,
            'square_side' => 4,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 4,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 0,
            'has_order' => false,
        ],
        8 => [
            'time_to_contemplate' => 6,
            'time_to_answer' => 8,
            'correct_answers_before_finish' => 3,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 2,
            'square_side' => 4,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 5,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 0,
            'has_order' => true,
        ],
        9 => [
            'time_to_contemplate' => 6,
            'time_to_answer' => 8,
            'correct_answers_before_finish' => 2,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 2,
            'square_side' => 4,
            'colors_amount' => 2,
            'successful_rounds_before_promotion' => 5,
            'failed_rounds_before_demotion' => 1,
            'rotation_iterations' => 0,
            'has_order' => true,
        ],
        10 => [
            'time_to_contemplate' => 5,
            'time_to_answer' => 6,
            'correct_answers_before_finish' => 3,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 2,
            'square_side' => 4,
            'colors_amount' => 2,
            'successful_rounds_before_promotion' => 6,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 0,
            'has_order' => true,
        ],
        11 => [
            'time_to_contemplate' => 5,
            'time_to_answer' => 6,
            'correct_answers_before_finish' => 3,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 2,
            'square_side' => 4,
            'colors_amount' => 2,
            'successful_rounds_before_promotion' => 6,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 1,
            'has_order' => true,
        ],
        12 => [
            'time_to_contemplate' => 5,
            'time_to_answer' => 6,
            'correct_answers_before_finish' => 3,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 4,
            'colors_amount' => 2,
            'successful_rounds_before_promotion' => 7,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 2,
            'has_order' => true,
        ],
        13 => [
            'time_to_contemplate' => 5,
            'time_to_answer' => 6,
            'correct_answers_before_finish' => 4,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 2,
            'square_side' => 5,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 7,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 0,
            'has_order' => false,
        ],
        14 => [
            'time_to_contemplate' => 4,
            'time_to_answer' => 6,
            'correct_answers_before_finish' => 6,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 2,
            'square_side' => 5,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 8,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 0,
            'has_order' => false,
        ],
        15 => [
            'time_to_contemplate' => 4,
            'time_to_answer' => 5,
            'correct_answers_before_finish' => 8,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 5,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 8,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 0,
            'has_order' => false,
        ],
        16 => [
            'time_to_contemplate' => 4,
            'time_to_answer' => 5,
            'correct_answers_before_finish' => 8,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 5,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 9,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 1,
            'has_order' => false,
        ],
        17 => [
            'time_to_contemplate' => 4,
            'time_to_answer' => 5,
            'correct_answers_before_finish' => 4,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 5,
            'colors_amount' => 2,
            'successful_rounds_before_promotion' => 9,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 2,
            'has_order' => false,
        ],
        18 => [
            'time_to_contemplate' => 3,
            'time_to_answer' => 5,
            'correct_answers_before_finish' => 4,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 5,
            'colors_amount' => 2,
            'successful_rounds_before_promotion' => 10,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 0,
            'has_order' => true,
        ],
        19 => [
            'time_to_contemplate' => 3,
            'time_to_answer' => 4,
            'correct_answers_before_finish' => 3,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 5,
            'colors_amount' => 3,
            'successful_rounds_before_promotion' => 10,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 1,
            'has_order' => true,
        ],
        20 => [
            'time_to_contemplate' => 3,
            'time_to_answer' => 4,
            'correct_answers_before_finish' => 3,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 5,
            'colors_amount' => 3,
            'successful_rounds_before_promotion' => 11,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 2,
            'has_order' => true,
        ],
        21 => [
            'time_to_contemplate' => 3,
            'time_to_answer' => 4,
            'correct_answers_before_finish' => 6,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 2,
            'square_side' => 6,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 10,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 0,
            'has_order' => false,
        ],
        22 => [
            'time_to_contemplate' => 3,
            'time_to_answer' => 4,
            'correct_answers_before_finish' => 8,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 2,
            'square_side' => 6,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 11,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 0,
            'has_order' => false,
        ],
        23 => [
            'time_to_contemplate' => 2,
            'time_to_answer' => 4,
            'correct_answers_before_finish' => 8,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 6,
            'colors_amount' => 1,
            'successful_rounds_before_promotion' => 12,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 1,
            'has_order' => false,
        ],
        24 => [
            'time_to_contemplate' => 2,
            'time_to_answer' => 3,
            'correct_answers_before_finish' => 5,
            'incorrect_answers_before_finish' => 3,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 6,
            'colors_amount' => 2,
            'successful_rounds_before_promotion' => 12,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 2,
            'has_order' => false,
        ],
        25 => [
            'time_to_contemplate' => 2,
            'time_to_answer' => 3,
            'correct_answers_before_finish' => 5,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 6,
            'colors_amount' => 2,
            'successful_rounds_before_promotion' => 12,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 0,
            'has_order' => true,
        ],
        26 => [
            'time_to_contemplate' => 2,
            'time_to_answer' => 3,
            'correct_answers_before_finish' => 5,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 6,
            'colors_amount' => 2,
            'successful_rounds_before_promotion' => 12,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 1,
            'has_order' => true,
        ],
        27 => [
            'time_to_contemplate' => 2,
            'time_to_answer' => 3,
            'correct_answers_before_finish' => 4,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 6,
            'colors_amount' => 3,
            'successful_rounds_before_promotion' => 12,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 1,
            'has_order' => false,
        ],
        28 => [
            'time_to_contemplate' => 2,
            'time_to_answer' => 2,
            'correct_answers_before_finish' => 4,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 6,
            'colors_amount' => 3,
            'successful_rounds_before_promotion' => 12,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 2,
            'has_order' => true,
        ],
        29 => [
            'time_to_contemplate' => 2,
            'time_to_answer' => 2,
            'correct_answers_before_finish' => 5,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 6,
            'colors_amount' => 3,
            'successful_rounds_before_promotion' => 12,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 2,
            'has_order' => true,
        ],
        30 => [
            'time_to_contemplate' => 2,
            'time_to_answer' => 2,
            'correct_answers_before_finish' => 5,
            'incorrect_answers_before_finish' => 2,
            'incorrect_answers_to_fail' => 1,
            'square_side' => 6,
            'colors_amount' => 3,
            'successful_rounds_before_promotion' => 12,
            'failed_rounds_before_demotion' => 2,
            'rotation_iterations' => 3,
            'has_order' => true,
        ],
    ]
];
