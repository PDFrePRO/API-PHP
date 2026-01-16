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
if (!function_exists('json_validate')) {
    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                             Definitions                                                            \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    define('POLYFILL_FUNCTION_JSON_VALIDATE_PROVIDED', true);

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                              Function                                                              \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * Checks if a string contains valid JSON.
     *
     * @description Returns whether the given {@see string} is syntactically valid JSON. If **json_validate()** returns {@see true},
     *              {@see json_decode()} will successfully decode the given string when using the same {@param $depth} and {@param $flags}.
     *
     *              If **json_validate()** returns {@see false}, the cause can be retrieved using {@see json_last_error()} and
     *              {@see json_last_error_msg()}.
     *
     *              **json_validate()** uses less memory than {@see json_decode()} if the decoded JSON payload is not used, because it does
     *              not need to build the array or object structure containing the payload.
     *
     *              @caution Calling **json_validate()** immediately before {@see json_decode()} will unnecessarily parse the string twice,
     *                       as {@see json_decode()} implicitly performs validation during decoding.
     *
     *                       **json_validate()** should therefore only be used if the decode JSON payload is not immediately used and
     *                       knowing whether the string contains valid JSON is needed.
     *
     * @param string $json  - The string to validate.
     *
     *                        This function only works with UTF-8 encoded strings.
     *
     *                        @note PHP implements a superset of JSON as specified in the original
     *                              [» RFC 7159]({@link https://datatracker.ietf.org/doc/html/rfc7159}).
     * @param int    $depth - Maximum nesting depth of the structure being decoded. The value must be greater than 0, and less than or equal
     *                        to 2147483647.
     * @param int    $flags - Currently only {@see JSON_INVALID_UTF8_IGNORE} is accepted.
     *
     * @return bool - Returns {@see true} if the given string is syntactically valid JSON, otherwise returns {@see false}.
     *
     * @throws ValueError - If {@param $depth} is outside the allowed range, a {@see ValueError} is thrown.
     * @throws ValueError - If {@param $flags} is not a valid flag, a {@see ValueError} is thrown.
     *
     * @see json_decode()         - Decodes a JSON string.
     * @see json_last_error()     - Returns the last error occurred.
     * @see json_last_error_msg() - Returns the error string of the last json_validate(), json_encode() or json_decode() call.
     */
    function json_validate(string $json, int $depth = 512, int $flags = 0): bool
    {
        json_decode($json, depth: $depth, flags: $flags);

        return json_last_error() === JSON_ERROR_NONE;
    }
}
