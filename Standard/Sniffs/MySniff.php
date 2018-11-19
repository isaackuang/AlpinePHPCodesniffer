<?php
namespace MyStandard\Sniffs\NamingConventions;

use PHP_CodeSniffer\Sniffs\AbstractVariableSniff;
use PHP_CodeSniffer\Util\Common;
use PHP_CodeSniffer\Files\File;

class VariableSniff extends AbstractVariableSniff
{
    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    protected function processVariable(File $phpcsFile, $stackPtr)
    {
        $tokens  = $phpcsFile->getTokens();
        $varName = ltrim($tokens[$stackPtr]['content'], '$');

        // If it's a php reserved var, then its ok.
        if (isset($this->phpReservedVars[$varName]) === true) {
            return;
        }

        if (Common::isCamelCaps($varName, false, true, false) === false) {
            $error = 'Variable "%s" is not in valid camel caps format';
            $data  = [$varName];
            $phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $data);
        }

    }//end processVariable()


    /**
     * Processes class member variables.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    protected function processMemberVar(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $varName     = ltrim($tokens[$stackPtr]['content'], '$');
        $memberProps = $phpcsFile->getMemberProperties($stackPtr);
        if (empty($memberProps) === true) {
            // Couldn't get any info about this variable, which
            // generally means it is invalid or possibly has a parse
            // error. Any errors will be reported by the core, so
            // we can ignore it.
            return;
        }

        $errorData = [$varName];

        if (substr($varName, 0, 1) === '_') {
            $error = 'Property "%s" must not contain a leading underscore';
            $data  = [$errorData[0]];
            $phpcsFile->addError($error, $stackPtr, 'PropertyHasUnderscore', $data);
        }

        if (Common::isCamelCaps($varName, false, true, false) === false) {
            $error = 'Property "%s" is not in valid camel caps format';
            $data  = [$originalVarName];
            $phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $data);
        }

    }//end processMemberVar()


    /**
     * Processes the variable found within a double quoted string.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the double quoted
     *                                               string.
     *
     * @return void
     */
    protected function processVariableInString(File $phpcsFile, $stackPtr)
    {

    }//end processVariableInString()


}//end class
