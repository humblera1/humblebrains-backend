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
    //    'main_image' => '',
    //    'thumbnail_image' => '',
    //    'icon_image'
    'category' => CategoryEnum::Memory,
    'levels' => [
        1 => [
            'square_side' => 3,
            'cells_amount_to_reproduce' => 1,
            'colors_amount' => 1,
            'correct_answers_before_promotion' => 1,
            'incorrect_answers_before_demotion' => 1,
            'rotation_iterations' => 0,
            'has_order' => 'false',
        ],
        2 => [
            'square_side' => 3,
            'cells_amount_to_reproduce' => 2,
            'colors_amount' => 1,
            'correct_answers_before_promotion' => 2,
            'incorrect_answers_before_demotion' => 3,
            'rotation_iterations' => 0,
            'has_order' => 'false',
        ]
    ]
];
