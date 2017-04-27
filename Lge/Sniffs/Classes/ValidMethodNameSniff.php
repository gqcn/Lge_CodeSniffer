<?php
/**
 * Lge_Sniffs_Classes_ValidMethodNameSniff
 * @author john
 */

if (class_exists('PHP_CodeSniffer_Standards_AbstractScopeSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_AbstractScopeSniff not found');
}

/**
 * Class Lge_Sniffs_Classes_ValidMethodNameSniff
 */
class Lge_Sniffs_Classes_ValidMethodNameSniff extends PHP_CodeSniffer_Standards_AbstractScopeSniff
{

    public $magicMethods = array(
                               'construct',
                               'destruct',
                               'call',
                               'callstatic',
                               'get',
                               'set',
                               'isset',
                               'unset',
                               'sleep',
                               'wakeup',
                               'tostring',
                               'set_state',
                               'clone',
                               'invoke',
                               'call',
                              );

    /**
     * Lge_Sniffs_Classes_ValidMethodNameSniff constructor.
     */
    public function __construct()
    {
        parent::__construct(array(T_CLASS, T_INTERFACE), array(T_FUNCTION));

    }

    /**
     * Processes the function tokens within the class.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param integer              $stackPtr  The position where the token was found.
     * @param integer              $currScope The current scope opener token.
     *
     * @return void
     */
    public function processTokenWithinScope(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $currScope)
    {
        $tokens = $phpcsFile->getTokens();

        $methodName = $phpcsFile->getDeclarationName($stackPtr);
        if ($methodName === null || $methodName === '__init') {
            // Ignore closures.
            return;
        }

        if (preg_match('|^__|', $methodName) !== 0) {
            $magicPart = strtolower(substr($methodName, 2));
            if (in_array($magicPart, $this->magicMethods) === false) {
                 $error = 'Method name "%s" is invalid; only PHP magic methods should be prefixed with a double underscore';
                 $phpcsFile->addError($error, $stackPtr, 'MethodDoubleUnderscore');
            }

            return;
        }

        $find   = PHP_CodeSniffer_Tokens::$scopeModifiers;
        $prev   = $phpcsFile->findPrevious($find, $stackPtr - 1, $stackPtr - 7, false);
        $public = false;
        if (empty($prev)) {
            $error = 'Method must be defined its scope using public,private or protected';
            $phpcsFile->addError($error, $stackPtr, 'MethodScope');
        } else {
            $public = ($tokens[$prev]['code'] == T_PUBLIC);
            if ($methodName[0] == '_' && $tokens[$prev]['code'] == T_PUBLIC) {
                $error = 'Method name with underscore prefix must be defined its scope using private or protected';
                $phpcsFile->addError($error, $stackPtr, 'MethodScope');
            } elseif (in_array($tokens[$prev]['code'], array(T_PRIVATE, T_PROTECTED)) && $methodName[0] != '_') {
                $error = 'Private or protected method must be defined its name with underscore prefix';
                $phpcsFile->addError($error, $stackPtr, 'MethodScope');
            } else {
                $valid = PHP_CodeSniffer::isCamelCaps($methodName, false, $public, false);
                if ($valid === false) {
                    $type  = lcfirst($methodName);
                    $error = '%s name "%s" is not in camel caps format';
                    $data  = array(
                        $type,
                        $methodName,
                    );
                    $phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $data);
                }
            }
        }

        $visibility = 0;
        $static     = 0;
        $abstract   = 0;
        $final      = 0;

        $find   = PHP_CodeSniffer_Tokens::$methodPrefixes;
        $find[] = T_WHITESPACE;
        $prev   = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);

        $prefix = $stackPtr;
        while (($prefix = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$methodPrefixes, ($prefix - 1), $prev)) !== false) {
            switch ($tokens[$prefix]['code']) {
                case T_STATIC:
                    $static = $prefix;
                    break;
                case T_ABSTRACT:
                    $abstract = $prefix;
                    break;
                case T_FINAL:
                    $final = $prefix;
                    break;
                default:
                    $visibility = $prefix;
                    break;
            }
        }

        if ($static !== 0 && $static < $visibility) {
            $error = 'The static declaration must come after the visibility declaration';
            $phpcsFile->addError($error, $static, 'StaticBeforeVisibility');
        }

        if ($visibility !== 0 && $final > $visibility) {
            $error = 'The final declaration must precede the visibility declaration';
            $phpcsFile->addError($error, $final, 'FinalAfterVisibility');
        }

        if ($visibility !== 0 && $abstract > $visibility) {
            $error = 'The abstract declaration must precede the visibility declaration';
            $phpcsFile->addError($error, $abstract, 'AbstractAfterVisibility');
        }

    }

}
