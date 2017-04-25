<?php
/**
 * Squiz_Sniffs_NamingConventions_ValidFunctionNameSniff.
 *
 * @author john
 */

if (class_exists('PEAR_Sniffs_NamingConventions_ValidFunctionNameSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PEAR_Sniffs_NamingConventions_ValidFunctionNameSniff not found');
}

/**
 * Squiz_Sniffs_NamingConventions_ValidFunctionNameSniff.
 */
class Lge_Sniffs_NamingConventions_ValidFunctionNameSniff extends PEAR_Sniffs_NamingConventions_ValidFunctionNameSniff
{

    /**
     * Processes the tokens outside the scope.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being processed.
     * @param integer              $stackPtr  The position where this token was
     *                                        found.
     *
     * @return void
     */
    public function processTokenOutsideScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $functionName = $phpcsFile->getDeclarationName($stackPtr);
        if ($functionName === null) {
            return;
        }

        $errorData = array($functionName);

        /*
        // Does this function claim to be magical?
        if (preg_match('|^__[^_]|', $functionName) !== 0) {
            $error = 'Function name "%s" is invalid; only PHP magic methods should be prefixed with a double underscore';
            $phpcsFile->addError($error, $stackPtr, 'DoubleUnderscore', $errorData);
            return;
        }
        */
        if (PHP_CodeSniffer::isCamelCaps($functionName, false, true, false) === false) {
            $error = 'Function name "%s" is not in camel caps format';
            $phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $errorData);
        }

    }

}
