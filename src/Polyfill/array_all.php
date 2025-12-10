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
     * Checks if all {@see array} elements satisfy a callback function.
     *
     * @description **array_all()** returns {@see true}, if the given {@param $callback} returns {@see true} for all elements. Otherwise the
     *              function returns {@see false}.
     *
     * @param array    $array    - The {@see array} that should be searched.
     * @param callable $callback - The callback function to call to check each element, which must be
     *
     *                             `callback(mixed $value, mixed $key): bool`
     *
     *                             If this function returns {@see false}, {@see false} is returned from **array_all()** and the callback
     *                             will not be called for further elements.
     *
     * @return bool - The function returns {@see true}, if {@param $callback} returns {@see true} for all elements. Otherwise, the function
     *                returns {@see false}.
     *
     * @see array_any()      - Checks if at least one array element satisfies a callback function.
     * @see array_filter()   - Filters elements of an array using a callback function.
     * @see array_find()     - Returns the first element satisfying a callback function.
     * @see array_find_key() - Returns the key of the first element satisfying a callback function.
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
