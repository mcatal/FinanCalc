<?php

/**
 * @param $objectType
 * @param $array
 * @return bool
 */
function isObjectTypeInArray($objectType, $array) {
    foreach ($array as $arrayEntry) {
        if ($arrayEntry instanceof $objectType) {
            return true;
        }
    }

    return false;
}