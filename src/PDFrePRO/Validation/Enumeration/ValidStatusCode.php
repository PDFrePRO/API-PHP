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

namespace PDFrePRO\Validation\Enumeration;

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                                 Usages                                                                 \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

use PDFrePRO\Core\Trait\BackedEnumCore;

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                               Enumeration                                                              \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

if (defined('POLYFILL_INTERFACE_BACKED_ENUM_PROVIDED')) {
    /**
     * @package     PDFrePRO
     * @version     v3.04
     * @author      RICHTER & POWELEIT GmbH
     * @description This enumeration provides all valid status codes, which are returned by the PDFrePRO API.
     * @link        https://www.pdfrepro.de/
     */
    class ValidStatusCode
    {
        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                             Traits                                                             \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        use BackedEnumCore;

        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                              Cases                                                             \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        /**
         */
        public static function BadRequest(): static
        {
            return new static('BadRequest', 400);
        }

        /**
         */
        public static function Unauthorized(): static
        {
            return new static('Unauthorized', 401);
        }

        /**
         */
        public static function NotFound(): static
        {
            return new static('NotFound', 404);
        }

        /**
         */
        public static function MethodNotAllowed(): static
        {
            return new static('MethodNotAllowed', 405);
        }

        /**
         */
        public static function NotAcceptable(): static
        {
            return new static('NotAcceptable', 406);
        }

        /**
         */
        public static function RequestTimeout(): static
        {
            return new static('RequestTimeout', 408);
        }

        /**
         */
        public static function Conflict(): static
        {
            return new static('Conflict', 409);
        }

        /**
         */
        public static function LengthRequired(): static
        {
            return new static('LengthRequired', 411);
        }

        /**
         */
        public static function InternalServerError(): static
        {
            return new static('InternalServerError', 500);
        }

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
            return [
                static::BadRequest(),
                static::Unauthorized(),
                static::NotFound(),
                static::MethodNotAllowed(),
                static::NotAcceptable(),
                static::RequestTimeout(),
                static::Conflict(),
                static::LengthRequired(),
                static::InternalServerError()
            ];
        }
    }
} else {
    /**
     * @package     PDFrePRO
     * @version     v3.04
     * @author      RICHTER & POWELEIT GmbH
     * @description This enumeration provides all valid status codes, which are returned by the PDFrePRO API.
     * @link        https://www.pdfrepro.de/
     */
    enum ValidStatusCode: int
    {
        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                             Traits                                                             \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        use BackedEnumCore;

        //********************************************************************************************************************************\\
        //                                                                                                                                \\
        //                                                              Cases                                                             \\
        //                                                                                                                                \\
        //********************************************************************************************************************************\\

        case BadRequest          = 400;
        case Unauthorized        = 401;
        case NotFound            = 404;
        case MethodNotAllowed    = 405;
        case NotAcceptable       = 406;
        case RequestTimeout      = 408;
        case Conflict            = 409;
        case LengthRequired      = 411;
        case InternalServerError = 500;
    }
}
