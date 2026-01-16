<?php

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                              Declarations                                                              \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

declare (strict_types = 1);

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                                Namespace                                                               \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

namespace PDFrePRO\Core\Trait;

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                                  Trait                                                                 \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

if (defined('POLYFILL_INTERFACE_UNIT_ENUM_PROVIDED')) {
    /**
     * @package     PDFrePRO
     * @version     v3.04
     * @author      RICHTER & POWELEIT GmbH
     * @description This trait provides basic functionalities to all unit enumerations, which are used by the PDFrePRO API.
     * @link        https://www.pdfrepro.de/
     */
    trait UnitEnumCore
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
        public static function cases(): array
        {
            return [];
        }

        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                       Instance Properties                                                      \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        /**
         */
        protected string $name;

        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                          Magic Methods                                                         \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        /**
         * @param string $name
         */
        public function __construct(string $name)
        {
            $this->name = $name;
        }

        /**
         * @param string $name
         *
         * @return mixed
         */
        public function __get(string $name): mixed
        {
            return $this->{$name};
        }
    }
} else {
    /**
     * @package     PDFrePRO
     * @version     v3.04
     * @author      RICHTER & POWELEIT GmbH
     * @description This trait provides basic functionalities to all unit enumerations, which are used by the PDFrePRO API.
     * @link        https://www.pdfrepro.de/
     */
    trait UnitEnumCore {}
}
