<?php

$process = function ($rowData, $arrayConfig, $faker, $rowIndex) {
    return array_map(function ($key, $value) use ($arrayConfig, $faker, $rowData, $rowIndex) {
        if (!array_key_exists($key, $arrayConfig)) {
            return $value;
        }
        if (is_callable($arrayConfig[$key])) {
            return $arrayConfig[$key]($value, $rowData, $rowIndex, $faker);
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

$transcoding = function ($rowData) {
    return array_map(function ($value) {
        $inCode = mb_detect_encoding($value, array('UTF-8', 'Windows-1251'));
        if ($inCode != iconv_get_encoding('output_encoding')) {
            $value = mb_convert_encoding($value, iconv_get_encoding('output_encoding'), $inCode);
        }
        return $value;
    }, $rowData);
};
