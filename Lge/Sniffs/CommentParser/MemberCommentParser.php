<?php
/**
 * Parses class member comments.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

if (class_exists('Lge_Sniffs_CommentParser_ClassCommentParser', true) === false) {
    $error = 'Class Lge_Sniffs_CommentParser_ClassCommentParser not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * Parses class member comments.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: 1.5.5
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Lge_Sniffs_CommentParser_MemberCommentParser extends Lge_Sniffs_CommentParser_ClassCommentParser
{

    /**
     * Represents a \@var tag in a member comment.
     *
     * @var Lge_Sniffs_CommentParser_SingleElement
     */
    private $_var = null;


    /**
     * Parses Var tags.
     *
     * @param array $tokens The tokens that represent this tag.
     *
     * @return Lge_Sniffs_CommentParser_SingleElement
     */
    protected function parseVar($tokens)
    {
        $this->_var = new Lge_Sniffs_CommentParser_SingleElement(
            $this->previousElement,
            $tokens,
            'var',
            $this->phpcsFile
        );

        return $this->_var;

    }//end parseVar()


    /**
     * Returns the var tag found in the member comment.
     *
     * @return Lge_Sniffs_CommentParser_PairElement
     */
    public function getVar()
    {
        return $this->_var;

    }//end getVar()


    /**
     * Returns the allowed tags for this parser.
     *
     * @return array
     */
    protected function getAllowedTags()
    {
        return array('var' => true);

    }//end getAllowedTags()


}//end class

?>
