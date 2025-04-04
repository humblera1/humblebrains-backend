<?php

namespace App\Console\Commands\Records;

use Illuminate\Console\Command;

abstract class AbstractRecordCommand extends Command
{
    const SKIP_MODE = 'skip';

    const UPDATE_MODE = 'update';

    /**
     * Encode translatable data to json.
     *
     * @param $data
     * @return void
     */
    protected function prepareTranslatableData(&$data): void {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
        }
    }
}
