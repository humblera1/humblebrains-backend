<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Следующие строки содержат сообщения об ошибках валидации по умолчанию,
    | используемые классом валидатора. Некоторые правила имеют несколько вариантов,
    | например правило проверки размера. Вы можете изменять эти сообщения для
    | лучшего соответствия вашему приложению.
    |
    */

    'accepted'             => 'Поле :attribute должно быть принято.',
    'accepted_if'          => 'Поле :attribute должно быть принято, когда :other равно :value.',
    'active_url'           => 'Поле :attribute должно быть действительным URL.',
    'after'                => 'Поле :attribute должно быть датой после :date.',
    'after_or_equal'       => 'Поле :attribute должно быть датой не раньше :date.',
    'alpha'                => 'Поле :attribute должно содержать только буквы.',
    'alpha_dash'           => 'Поле :attribute может содержать только буквы, цифры, дефисы и символы подчёркивания.',
    'alpha_num'            => 'Поле :attribute должно содержать только буквы и цифры.',
    'array'                => 'Поле :attribute должно быть массивом.',
    'ascii'                => 'Поле :attribute должно содержать только однобайтовые буквы, цифры и символы.',
    'before'               => 'Поле :attribute должно быть датой до :date.',
    'before_or_equal'      => 'Поле :attribute должно быть датой, не позже :date.',
    'between'              => [
        'array'   => 'Поле :attribute должно содержать от :min до :max элементов.',
        'file'    => 'Поле :attribute должно быть размером от :min до :max килобайт.',
        'numeric' => 'Поле :attribute должно быть между :min и :max.',
        'string'  => 'Поле :attribute должно содержать от :min до :max символов.',
    ],
    'boolean'              => 'Поле :attribute должно быть истинным или ложным.',
    'can'                  => 'Поле :attribute содержит неразрешённое значение.',
    'confirmed'            => 'Подтверждение поля :attribute не совпадает.',
    'contains'             => 'Поле :attribute не содержит необходимое значение.',
    'current_password'     => 'Пароль неверен.',
    'date'                 => 'Поле :attribute не является действительной датой.',
    'date_equals'          => 'Поле :attribute должно быть датой, равной :date.',
    'date_format'          => 'Поле :attribute не соответствует формату :format.',
    'decimal'              => 'Поле :attribute должно содержать :decimal знаков после запятой.',
    'declined'             => 'Поле :attribute должно быть отклонено.',
    'declined_if'          => 'Поле :attribute должно быть отклонено, когда :other равно :value.',
    'different'            => 'Значения полей :attribute и :other должны различаться.',
    'digits'               => 'Поле :attribute должно содержать :digits цифр.',
    'digits_between'       => 'Поле :attribute должно содержать от :min до :max цифр.',
    'dimensions'           => 'Поле :attribute имеет недопустимые размеры изображения.',
    'distinct'             => 'Поле :attribute содержит дублирующееся значение.',
    'doesnt_end_with'      => 'Поле :attribute не должно заканчиваться одним из следующих значений: :values.',
    'doesnt_start_with'    => 'Поле :attribute не должно начинаться с одного из следующих значений: :values.',
    'email'                => 'Поле :attribute должно быть действительным e-mail адресом.',
    'ends_with'            => 'Поле :attribute должно заканчиваться одним из следующих значений: :values.',
    'enum'                 => 'Выбранное значение для :attribute недействительно.',
    'exists'               => 'Выбранное значение для :attribute недействительно.',
    'extensions'           => 'Поле :attribute должно иметь одно из следующих расширений: :values.',
    'file'                 => 'Поле :attribute должно быть файлом.',
    'filled'               => 'Поле :attribute обязательно для заполнения.',
    'gt'                   => [
        'array'   => 'Поле :attribute должно содержать более чем :value элементов.',
        'file'    => 'Поле :attribute должно быть больше :value килобайт.',
        'numeric' => 'Поле :attribute должно быть больше :value.',
        'string'  => 'Поле :attribute должно содержать более чем :value символов.',
    ],
    'gte'                  => [
        'array'   => 'Поле :attribute должно содержать не менее :value элементов.',
        'file'    => 'Поле :attribute должно быть не меньше :value килобайт.',
        'numeric' => 'Поле :attribute должно быть не меньше :value.',
        'string'  => 'Поле :attribute должно содержать не менее :value символов.',
    ],
    'hex_color'            => 'Поле :attribute должно быть допустимым шестнадцатеричным цветом.',
    'image'                => 'Поле :attribute должно быть изображением.',
    'in'                   => 'Выбранное значение для :attribute недействительно.',
    'in_array'             => 'Поле :attribute не существует в :other.',
    'integer'              => 'Поле :attribute должно быть целым числом.',
    'ip'                   => 'Поле :attribute должно быть действительным IP-адресом.',
    'ipv4'                 => 'Поле :attribute должно быть действительным IPv4-адресом.',
    'ipv6'                 => 'Поле :attribute должно быть действительным IPv6-адресом.',
    'json'                 => 'Поле :attribute должно быть действительной JSON строкой.',
    'list'                 => 'Поле :attribute должно быть списком.',
    'lowercase'            => 'Поле :attribute должно состоять из строчных букв.',
    'lt'                   => [
        'array'   => 'Поле :attribute должно содержать меньше чем :value элементов.',
        'file'    => 'Поле :attribute должно быть меньше :value килобайт.',
        'numeric' => 'Поле :attribute должно быть меньше :value.',
        'string'  => 'Поле :attribute должно содержать меньше чем :value символов.',
    ],
    'lte'                  => [
        'array'   => 'Поле :attribute не должно содержать более чем :value элементов.',
        'file'    => 'Поле :attribute должно быть не больше :value килобайт.',
        'numeric' => 'Поле :attribute должно быть не больше :value.',
        'string'  => 'Поле :attribute должно содержать не больше :value символов.',
    ],
    'mac_address'          => 'Поле :attribute должно быть действительным MAC-адресом.',
    'max'                  => [
        'array'   => 'Поле :attribute не должно содержать более чем :max элементов.',
        'file'    => 'Поле :attribute не должно быть больше :max килобайт.',
        'numeric' => 'Поле :attribute не должно быть больше :max.',
        'string'  => 'Поле :attribute не должно содержать более чем :max символов.',
    ],
    'max_digits'           => 'Поле :attribute не должно содержать более чем :max цифр.',
    'mimes'                => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'mimetypes'            => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'min'                  => [
        'array'   => 'Поле :attribute должно содержать не менее :min элементов.',
        'file'    => 'Поле :attribute должно быть не меньше :min килобайт.',
        'numeric' => 'Поле :attribute должно быть не меньше :min.',
        'string'  => 'Поле :attribute должно содержать не менее :min символов.',
    ],
    'min_digits'           => 'Поле :attribute должно содержать не менее :min цифр.',
    'missing'              => 'Поле :attribute должно отсутствовать.',
    'missing_if'           => 'Поле :attribute должно отсутствовать, когда :other равно :value.',
    'missing_unless'       => 'Поле :attribute должно отсутствовать, если :other не равно :value.',
    'missing_with'         => 'Поле :attribute должно отсутствовать, когда присутствует :values.',
    'missing_with_all'     => 'Поле :attribute должно отсутствовать, когда присутствуют :values.',
    'multiple_of'          => 'Поле :attribute должно быть кратно :value.',
    'not_in'               => 'Выбранное значение для :attribute недействительно.',
    'not_regex'            => 'Формат поля :attribute недействителен.',
    'numeric'              => 'Поле :attribute должно быть числом.',
    'password'             => [
        'letters'       => 'Поле :attribute должно содержать хотя бы одну букву.',
        'mixed'         => 'Поле :attribute должно содержать как минимум одну заглавную и одну строчную букву.',
        'numbers'       => 'Поле :attribute должно содержать хотя бы одну цифру.',
        'symbols'       => 'Поле :attribute должно содержать хотя бы один символ.',
        'uncompromised' => 'Указанное значение :attribute оказалось скомпрометированным. Пожалуйста, выберите другое значение.',
    ],
    'present'              => 'Поле :attribute должно присутствовать.',
    'present_if'           => 'Поле :attribute должно присутствовать, когда :other равно :value.',
    'present_unless'       => 'Поле :attribute должно присутствовать, если :other не равно :value.',
    'present_with'         => 'Поле :attribute должно присутствовать, когда :values присутствуют.',
    'present_with_all'     => 'Поле :attribute должно присутствовать, когда :values присутствуют.',
    'prohibited'           => 'Поле :attribute запрещено.',
    'prohibited_if'        => 'Поле :attribute запрещено, когда :other равно :value.',
    'prohibited_unless'    => 'Поле :attribute запрещено, если :other не находится в :values.',
    'prohibits'            => 'Поле :attribute запрещает наличие :other.',
    'regex'                => 'Формат поля :attribute недействителен.',
    'required'             => 'Поле :attribute обязательно для заполнения.',
    'required_array_keys'  => 'Поле :attribute должно содержать записи для: :values.',
    'required_if'          => 'Поле :attribute обязательно, когда :other равно :value.',
    'required_if_accepted' => 'Поле :attribute обязательно, когда :other принято.',
    'required_if_declined' => 'Поле :attribute обязательно, когда :other отклонено.',
    'required_unless'      => 'Поле :attribute обязательно, если :other не равно :values.',
    'required_with'        => 'Поле :attribute обязательно, когда присутствует :values.',
    'required_with_all'    => 'Поле :attribute обязательно, когда присутствуют :values.',
    'required_without'     => 'Поле :attribute обязательно, когда :values отсутствуют.',
    'required_without_all' => 'Поле :attribute обязательно, когда отсутствуют все из :values.',
    'same'                 => 'Значение :attribute должно совпадать с :other.',
    'size'                 => [
        'array'   => 'Поле :attribute должно содержать :size элементов.',
        'file'    => 'Поле :attribute должно быть размером :size килобайт.',
        'numeric' => 'Поле :attribute должно быть равным :size.',
        'string'  => 'Поле :attribute должно содержать :size символов.',
    ],
    'starts_with'          => 'Поле :attribute должно начинаться с одного из следующих значений: :values.',
    'string'               => 'Поле :attribute должно быть строкой.',
    'timezone'             => 'Поле :attribute должно быть действительным часовым поясом.',
    'unique'               => 'Такое значение поля :attribute уже занято.',
    'uploaded'             => 'Не удалось загрузить файл в поле :attribute.',
    'uppercase'            => 'Поле :attribute должно содержать только заглавные буквы.',
    'url'                  => 'Поле :attribute должно быть действительным URL.',
    'ulid'                 => 'Поле :attribute должно быть действительным ULID.',
    'uuid'                 => 'Поле :attribute должно быть действительным UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Здесь вы можете указать кастомные сообщения для атрибутов, используя
    | соглашение "attribute.rule" для именования строк. Это позволяет быстро
    | указать конкретное сообщение для конкретного правила валидации.
    |
    */

    'custom' => [],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Следующие строки используются для преобразования имен атрибутов
    | в более понятные для пользователя. Например, вместо "email" можно указать
    | "электронный адрес".
    |
    */

    'attributes' => [
        'usermail' => 'имя пользователя/e-mail',
    ],

];
