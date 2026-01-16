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
if (!interface_exists('UnitEnum')) {
    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                             Definitions                                                            \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    define('POLYFILL_INTERFACE_UNIT_ENUM_PROVIDED', true);

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                              Interface                                                             \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * The **UnitEnum** interface is automatically applied to all enumerations by the engine. It may not be implemented by user-defined
     * classes. Enumerations may not override its methods, as default implementations are provided by the engine. It is available only for
     * type checks.
     */
    interface UnitEnum
    {
        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                        Static Functions                                                        \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        /**
         * Generates a list of cases on an enum.
         *
         * @description - This method will return a packed array of all cases in an enumeration, in order of declaration.
         *
         * @return array - An array of all defined cases of this enumeration, in order of declaration.
         */
        public static function cases(): array;
    }
}
