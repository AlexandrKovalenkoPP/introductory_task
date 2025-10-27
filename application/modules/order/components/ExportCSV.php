<?php

namespace order\components;

use yii\db\Query;

/**
 * Компонент для Экспорта в CSV
 */
class ExportCSV
{

    /**
     * Метод экспорта в CSV из запроса
     *
     * @param string $name Название файла
     * @param Query $query Билдер запроса
     * @param array $headers Массив с заголовками таблицы
     * @return object
     */
    public static function exportFromQuery(string $name, Query $query, array $headers): object
    {
        $fileName = $name . '.csv';

        $stream = fopen('php://temp', 'r+');

        fwrite($stream, "\xEF\xBB\xBF");

        fputcsv($stream, $headers, ';');

        foreach ($query->batch(1000) as $rows) {
            foreach ($rows as $row) {
                $data = [];
                foreach ($headers as $header) {
                    $data[] = $row[$header];
                }
                fputcsv($stream, $data, ';');
            }
        }

        rewind($stream);

        return (object) [
            'stream' => $stream,
            'fileName' => $fileName
        ];
    }
}