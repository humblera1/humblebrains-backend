<?php

return [
    [
        'name' => 'square_side',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Square side',
            'ru' => 'Сторона квадрата',
        ],
        'description' => [
            'en' => 'Square side of the game',
            'ru' => 'Сторона квадрата',
        ],
    ],
    [
        'name' => 'cells_amount_to_reproduce',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Number of cells to reproduce',
            'ru' => 'Количество ячеек для запоминания',
        ],
        'description' => [
            'en' => 'Number of cells that need to be remembered and then reproduced',
            'ru' => 'Количество ячеек, которые необходимо запомнить и воспроизвести',
        ],
    ],
    [
        'name' => 'colors_amount',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Number of colors',
            'ru' => 'Количество цветов',
        ],
        'description' => [
            'en' => 'Number of different colors on the playing field',
            'ru' => 'Количество различных цветов на игровом поле',
        ],
    ],
    [
        'name' => 'correct_answers_before_promotion',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Number of correct answers before leveling up',
            'ru' => 'Количество правильных ответов до повышения уровня',
        ],
        'description' => [
            'en' => 'After this number of correct answers given in a row, the level will be increased.',
            'ru' => 'После указанного количества правильных ответов, данных подряд, уровень будет повышен',
        ],
    ],
    [
        'name' => 'incorrect_answers_before_demotion',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Number of incorrect answers before level decreases',
            'ru' => 'Количество неправильных ответов до понижения уровня',
        ],
        'description' => [
            'en' => 'After this number of incorrect answers given in a row, the level will be reduced to the previous one',
            'ru' => 'После указанного количества неправильных ответов, данных подряд, уровень будет понижен до предыдущего',
        ],
    ],
    [
        'name' => 'rotation_iterations',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Number of rotations',
            'ru' => 'Количество поворотов',
        ],
        'description' => [
            'en' => 'The number of rotations the playing field will make',
            'ru' => 'Количество поворотов, которое совершает игровое поле',
        ],
    ],
    [
        'name' => 'has_order',
        'type' => \App\Enums\TypeEnum::Boolean,
        'label' => [
            'en' => 'Checking the order',
            'ru' => 'Проверка порядка',
        ],
        'description' => [
            'en' => 'Should the game additionally check that the order in which cells are opened matches the order in which they are shown to the user',
            'ru' => 'Следует ли дополнительно проверять соответствие порядка открытия ячеек порядку их показа пользователю',
        ],
    ],

    // todo: время на запоминание и время на ответ
];
