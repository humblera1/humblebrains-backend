<?php

namespace App\Console\Commands\Records;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportMessages extends AbstractRecordCommand
{
    const DEFAULT_MESSAGES_FILENAME = 'index';

    protected $messagesTable = 'messages';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-messages {--mode=skip : The mode of operation (skip|update)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts translatable messages or update existing ones';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $messages = config('messages');

        foreach ($messages as $batchKey => $messageBatch) {
            $this->prepareTranslatableData($messageBatch);

             foreach ($messageBatch as $key => $message) {
                 $this->insertSingleMessage($key, $message, $batchKey);
             }
        }

        $this->info('All messages have been imported successfully.');
    }

    protected function insertSingleMessage(string $key, string $message, string $prefix): void
    {
        $prefixedKey = $prefix === self::DEFAULT_MESSAGES_FILENAME ? $key : $prefix . ':' . $key;

         $isMessageExists =  DB::table($this->messagesTable)->where('key', $prefixedKey)->exists();
         $now = now();

         $messageData = [
             'key' => $prefixedKey,
             'content' => $message,
             'created_at'  => $now,
             'updated_at'  => $now,
         ];

         if (!$isMessageExists) {
              DB::table($this->messagesTable)->insert($messageData);

              return;
         }

        if ($this->option('mode') ===  self::UPDATE_MODE) {
            DB::table('properties')->where('key', $prefixedKey)->update($messageData);

            $this->warn("Message '{$prefixedKey}' already exists, updated!");
        } else {
            $this->warn("Message '{$prefixedKey}' already exists, skipping.");
        }
    }
}
