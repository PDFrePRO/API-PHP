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
 * @description This polyfill is used in case the original PHP interface is not available.
 * @link        https://www.pdfrepro.de/
 */
if (!interface_exists('BackedEnum')) {
    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                             Definitions                                                            \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    define('POLYFILL_INTERFACE_BACKED_ENUM_PROVIDED', true);

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                              Interface                                                             \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * The **BackedEnum** interface is automatically applied to backed enumerations by the engine. It may not be implemented by user-defined
     * classes. Enumerations may not override its methods, as default implementations are provided by the engine. It is available only for
     * type checks.
     */
    interface BackedEnum extends UnitEnum
    {
        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                        Static Functions                                                        \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        /**
         * Maps a scalar to an enum instance.
         *
         * @description - The {@see from()} method translates a {@see string} or {@see int} into the corresponding Enum case, if any. If
         *                there is no matching case defined, it will throw a {@see ValueError}.
         *
         * @param int | string $value - The scalar value to map to an enum case.
         *
         * @return static - A case instance of this enumeration.
         *
         * @throws ValueError
         *
         * @see UnitEnum::cases()     - Generates a list of cases on an enum.
         * @see BackedEnum::tryFrom() - Maps a scalar to an enum instance or null.
         */
        public static function from(int | string $value): static;

        /**
         * Maps a scalar to an enum instance or null.
         *
         * @description - The {@see tryFrom()} method translates a {@see string} or {@see int} into the corresponding Enum case, if any. If
         *                there is no matching case defined, it will return null.
         *
         * @param int | string $value - The scalar value to map to an enum case.
         *
         * @return ?static - A case instance of this enumeration, or {@see null} if not found.
         *
         * @see UnitEnum::cases()  - Generates a list of cases on an enum.
         * @see BackedEnum::from() - Maps a scalar to an enum instance.
         */
        public static function tryFrom(int | string $value): ?static;
    }
}
