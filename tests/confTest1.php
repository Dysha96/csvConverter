<?php

return [
    1 => function ($value, $rowData, $rowIndex, $faker) {
        return $value . $rowIndex;
    },
    2 => null, // set value to null
    3 => function ($value, $rowData, $rowIndex, $faker) {
        return 'SЫ';
    },
    7 => function ($value, $rowData, $rowIndex, $faker) {
        return $value >> $rowIndex;
    },
];
