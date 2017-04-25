<?php
/**
 * Verifies that properties are declared correctly.
 * @author john
 */

if (class_exists('PHP_CodeSniffer_Standards_AbstractVariableSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_AbstractVariableSniff not found');
}

/**
 * Verifies that properties are declared correctly.
 */
class Lge_Sniffs_Classes_PropertyDeclarationSniff extends PHP_CodeSniffer_Standards_AbstractVariableSniff
{

    /**
     * Processes the function tokens within the class.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param integer              $stackPtr  The position where the token was found.
     *
     * @return void
     */
    public function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Detect multiple properties defined at the same time. Throw an error
        // for this, but also only process the first property in the list so we don't
        // repeat errors.
        $find = PHP_CodeSniffer_Tokens::$scopeModifiers;
        $find = array_merge($find, array(T_VARIABLE, T_VAR, T_SEMICOLON));
        $prev = $phpcsFile->findPrevious($find, ($stackPtr - 1));
        if ($tokens[$prev]['code'] === T_VARIABLE) {
            return;
        }
        if ($tokens[$prev]['code'] === T_VAR) {
            $error = 'The var keyword must not be used to declare a property';
            $phpcsFile->addError($error, $stackPtr, 'VarUsed');
        }

        /*
         * 变量名称下划线判断.
         */
        if ($tokens[$prev]['code'] === T_PUBLIC && $tokens[$stackPtr]['content'][1] === '_') {
            $error = 'Public property "%s" should not be prefixed with an underscore to indicate visibility';
            $data  = array($tokens[$stackPtr]['content']);
            $phpcsFile->addError($error, $stackPtr, 'PublicHasUnderscore', $data);
        } elseif (in_array($tokens[$prev]['code'], array(T_PRIVATE, T_PROTECTED)) && $tokens[$stackPtr]['content'][1] !== '_') {
            $visibilityStr = ($tokens[$prev]['code'] == T_PRIVATE) ? 'Private' : 'Protected';
            $error = "{$visibilityStr} property \"%s\" should be prefixed with an underscore to indicate visibility";
            $data  = array($tokens[$stackPtr]['content']);
            $phpcsFile->addError($error, $stackPtr, 'PrivateNoUnderscore', $data);
        }

        $next = $phpcsFile->findNext(array(T_VARIABLE, T_SEMICOLON), ($stackPtr + 1));
        if ($tokens[$next]['code'] === T_VARIABLE) {
            $error = 'There must not be more than one property declared per statement';
            $phpcsFile->addError($error, $stackPtr, 'Multiple');
        }

        $modifier = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$scopeModifiers, $stackPtr);
        if (($modifier === false) || ($tokens[$modifier]['line'] !== $tokens[$stackPtr]['line'])) {
            $error = 'Visibility must be declared on property "%s"';
            $data  = array($tokens[$stackPtr]['content']);
            $phpcsFile->addError($error, $stackPtr, 'ScopeMissing', $data);
        }

    }

    /**
     * Processes normal variables.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param integer              $stackPtr  The position where the token was found.
     *
     * @return void
     */
    public function processVariable(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        // We don't care about normal variables.
    }

    /**
     * Processes variables in double quoted strings.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param integer              $stackPtr  The position where the token was found.
     *
     * @return void
     */
    public function processVariableInString(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        // We don't care about normal variables.
    }

}//end class
