<?php

namespace order\components;

use yii\db\Migration;

class MigrateSQL extends Migration
{
    public function executeSqlFile(string $path): void
    {
        $sql = file_get_contents($path);
        $commands = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($commands as $command) {
            $this->execute($command);
        }
    }
}