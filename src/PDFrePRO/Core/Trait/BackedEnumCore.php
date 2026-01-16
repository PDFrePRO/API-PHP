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
//                                                                 Usages                                                                 \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

use BackedEnum;
use ValueError;

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                                  Trait                                                                 \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

if (defined('POLYFILL_INTERFACE_BACKED_ENUM_PROVIDED')) {
    /**
     * @package     PDFrePRO
     * @version     v3.04
     * @author      RICHTER & POWELEIT GmbH
     * @description This trait provides basic functionalities to all backed enumerations, which are used by the PDFrePRO API.
     * @link        https://www.pdfrepro.de/
     */
    trait BackedEnumCore
    {
        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                             Traits                                                             \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        use UnitEnumCore {
            __construct as UnitEnumCore___construct;
        }

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
        public static function from(int | string $value): static
        {
            foreach (static::cases() as $case) { /** @var BackedEnum $case */
                if ($case->value == $value) {
                    return $case;
                }
            }

            throw new ValueError(json_encode($value) . ' is not a valid backing value for enum ' . static::class);
        }

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
        public static function tryFrom(int | string $value): ?static
        {
            try {
                return static::from($value);
            } catch (ValueError) {
                return null;
            }
        }

        /**
         * Generates a list of cases' values on an enum.
         *
         * @description - This method will return a packed array of all cases' values in an enumeration, in order of declaration.
         *
         * @return array - An array of all defined cases' values of this enumeration, in order of declaration.
         */
        public static function values(): array
        {
            return array_map(
                function (BackedEnum $case): int | string {
                    return $case->value;
                },
                static::cases()
            );
        }

        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                       Instance Properties                                                      \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        /**
         */
        protected int | string $value;

        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                          Magic Methods                                                         \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        /**
         * @param string       $name
         * @param int | string $value
         */
        public function __construct(string $name, int | string $value)
        {
            $this->UnitEnumCore___construct($name);

            $this->value = $value;
        }
    }
} else {
    /**
     * @package     PDFrePRO
     * @version     v3.04
     * @author      RICHTER & POWELEIT GmbH
     * @description This trait provides basic functionalities to all backed enumerations, which are used by the PDFrePRO API.
     * @link        https://www.pdfrepro.de/
     */
    trait BackedEnumCore
    {
        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                             Traits                                                             \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        use UnitEnumCore;

        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                        Static Functions                                                        \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        /**
         * Generates a list of cases' values on an enum.
         *
         * @description - This method will return a packed array of all cases' values in an enumeration, in order of declaration.
         *
         * @return array - An array of all defined cases' values of this enumeration, in order of declaration.
         */
        public static function values(): array
        {
            return array_map(
                function (BackedEnum $case): int | string {
                    return $case->value;
                },
                static::cases()
            );
        }
    }
}
