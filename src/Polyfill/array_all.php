<?php

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                              Declarations                                                              \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

declare (strict_types = 1);

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                                Polyfill                                                                \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

/**
 * @package     PDFrePRO
 * @version     v3.04
 * @author      RICHTER & POWELEIT GmbH
 * @description This polyfill is used in case the original PHP function is not available.
 * @link        https://www.pdfrepro.de/
 */
if (!function_exists('array_all')) {
    /**
     * Checks if all array elements satisfy a callback function.
     *
     * @param array    $array    - The array that should be searched.
     * @param callable $callback - The callback function to call to check each element, which must be
     *                             `callback(mixed $value, mixed $key): bool`
     *                             @note If this function returns {@see false}, {@see false} is returned from **array_all()** and the
     *                                   callback will not be called for further elements.
     *
     * @return bool - The function returns {@see true}, if {@param $callback} returns {@see true} for all elements. Otherwise, the function
     *                returns {@see false}.
     */
    function array_all(array $array, callable $callback): bool
    {
        foreach ($array as $key => $value) {
            if (!$callback($value, $key)) {
                return false;
            }
        }

        return true;
    }
}
