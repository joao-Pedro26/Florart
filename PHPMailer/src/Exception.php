<?php
namespace PHPMailer\PHPMailer;

use Exception as GlobalException;

/**
 * PHPMailer Exception class.
 * PHP Version 5.5.
 *
 * @see https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 */
class Exception extends GlobalException
{
    /**
     * Prettify error message output.
     *
     * @return string
     */
    public function errorMessage()
    {
        return '<strong>' . htmlspecialchars($this->getMessage(), ENT_COMPAT | ENT_HTML401) . "</strong><br />\n";
    }
}