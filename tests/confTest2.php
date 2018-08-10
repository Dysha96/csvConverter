<?php

return [
    1 => function ($value, $rowData, $rowIndex, $faker) {
        return 'фыва вфы С' ;
    },
    2 => null, // set value to null
    3 => function ($value, $rowData, $rowIndex, $faker) {
            return $rowIndex;
    },
];
