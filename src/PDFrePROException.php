<?php

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                                Throwable                                                               \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

/**
 * @package     PDFrePRO
 * @version     v3.04
 * @author      RICHTER & POWELEIT GmbH
 * @description This throwable will be thrown by the PDFrePRO library on any error.
 * @link        https://www.pdfrepro.de/
 */
class PDFrePROException extends Exception
{
    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                        Error Code Constants                                                        \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * This code is set for {@see PDFrePROException}s by default.
     */
    public const CODE_DEFAULT                      = 0;

    /**
     * This code is set for {@see PDFrePROException}s, if the minimum version requirement for PHP is not met.
     */
    public const CODE_MINIMUM_REQUIRED_PHP_VERSION = 1;

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                            Magic Methods                                                           \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * The constructor of this throwable.
     *
     * @param  string    $message  - An optional error message for this throwable.
     * @param  int       $code     - An optional error code for this throwable.
     * @param ?Throwable $previous - An optional throwable, which were thrown prior to this throwable.
     *
     * @constructor
     */
    public function __construct(string $message = '', int $code = self::CODE_DEFAULT, ?Throwable $previous = null)
    {
        // Call the parent constructor.
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns a string representation of this throwable.
     *
     * @return string - A string representation of this throwable.
     */
    public function __toString(): string
    {
        return static::class . ' (' . $this->code . '): ' . $this->message;
    }
}
