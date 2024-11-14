<?php

return [
    // generals
    [
        'name' => 'time_to_contemplate',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Time for contemplation',
            'ru' => 'Время на запоминание',
        ],
        'description' => [
            'en' => 'Maximum amount of time allotted for contemplation (in seconds)',
            'ru' => 'Максимальное количество времени, отведенного на запоминание (в секундах)',
        ],
    ],
    [
        'name' => 'time_to_answer',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Time for answering',
            'ru' => 'Время на ответ',
        ],
        'description' => [
            'en' => 'Maximum amount of time allotted for answering (in seconds)',
            'ru' => 'Максимальное количество времени, отведенного на ответ (в секундах)',
        ],
    ],
    [
        'name' => 'correct_answers_before_finish',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Number of correct answers required to complete the round',
            'ru' => 'Количество верных ответов, необходимых для завершения раунда',
        ],
        'description' => [
            'en' => 'This is the total number of correct answers expected from the user in the round',
            'ru' => 'Это общее число верных ответов, которое ожидается от пользователя в текущем раунде игры',
        ],
    ],
    [
        'name' => 'incorrect_answers_before_finish',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Number of incorrect answers after which the round will end automatically',
            'ru' => 'Количество неверных ответов, после которых раунд будет завершен автоматически',
        ],
        'description' => [
            'en' => 'After a given number of incorrect answers, the round automatically ends',
            'ru' => 'После данного числа неверных ответов раунд автоматически завершается',
        ],
    ],
    [
        'name' => 'incorrect_answers_to_fail',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Allowable number of incorrect answers',
            'ru' => 'Допустимое количество неверных ответов',
        ],
        'description' => [
            'en' => 'After this number of incorrect answers, the round will be counted as unsuccessful, but will not be completed automatically',
            'ru' => 'После данного количества неверных ответов раунд будет засчитан как неуспешный, но не будет завершен автоматически',
        ],
    ],
    [
        'name' => 'successful_rounds_before_promotion',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Number of successful rounds before leveling up',
            'ru' => 'Количество успешных раундов до повышения уровня',
        ],
        'description' => [
            'en' => 'After this number of successfully completed rounds in a row, the level will be increased.',
            'ru' => 'После указанного количества успешно завершенных раундов, идущих подряд, уровень будет повышен',
        ],
    ],
    [
        'name' => 'failed_rounds_before_demotion',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Number of failed rounds before level decreases',
            'ru' => 'Количество неудачных раундов до понижения уровня',
        ],
        'description' => [
            'en' => 'After this number of failed rounds, the level will be reduced to the previous one',
            'ru' => 'После указанного количества раундов, завершенных с ошибкой, уровень будет понижен до предыдущего',
        ],
    ],
    [
        'name' => 'points_per_answer',
        'type' => \App\Enums\TypeEnum::Integer,
        'label' => [
            'en' => 'Number of points for correct answer',
            'ru' => 'Количество очков за верный ответ',
        ],
        'description' => [
            'en' => 'This number of points is given to the user after each correct answer.',
            'ru' => 'Данное количество очков выдается пользователю после каждого верного ответа',
        ],
    ],

    // matrix
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
    /**
     * @deprecated
     * @see correct_answers_before_finish
     */
    //    [
    //        'name' => 'cells_amount_to_reproduce',
    //        'type' => \App\Enums\TypeEnum::Integer,
    //        'label' => [
    //            'en' => 'Number of cells to reproduce',
    //            'ru' => 'Количество ячеек для запоминания',
    //        ],
    //        'description' => [
    //            'en' => 'Number of cells that need to be remembered and then reproduced',
    //            'ru' => 'Количество ячеек, которые необходимо запомнить и воспроизвести',
    //        ],
    //    ],
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
];
