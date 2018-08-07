<?php

$process = function ($rowData, $arrayConfig, $faker) {
    return array_map(function ($key, $value) use ($arrayConfig, $faker, $rowData) {
        if (!array_key_exists($key, $arrayConfig)) {
            return $value;
        }
        if (is_callable($arrayConfig[$key])) {
            return $arrayConfig[$key]($value, $rowData, $key, $faker);
        }
        $fakerType = $arrayConfig[$key];
        try {
            return $faker->$fakerType;
        } catch (Exception $exception) {
            return $fakerType;
        }
    }, array_keys($rowData), $rowData);
};

$record = function ($file, $rowData, $delimiter, $enclosure, $escape) {
    $file->fputcsv($rowData, $delimiter, $enclosure, $escape);
};