<?php

namespace MailPoetVendor\Egulias\EmailValidator\Parser;

if (!defined('ABSPATH')) exit;


use MailPoetVendor\Egulias\EmailValidator\Exception\DotAtEnd;
use MailPoetVendor\Egulias\EmailValidator\Exception\DotAtStart;
use MailPoetVendor\Egulias\EmailValidator\EmailLexer;
use MailPoetVendor\Egulias\EmailValidator\Exception\ExpectingAT;
use MailPoetVendor\Egulias\EmailValidator\Exception\ExpectingATEXT;
use MailPoetVendor\Egulias\EmailValidator\Exception\UnclosedQuotedString;
use MailPoetVendor\Egulias\EmailValidator\Exception\UnopenedComment;
use MailPoetVendor\Egulias\EmailValidator\Warning\CFWSWithFWS;
use MailPoetVendor\Egulias\EmailValidator\Warning\LocalTooLong;
class LocalPart extends \MailPoetVendor\Egulias\EmailValidator\Parser\Parser
{
    public function parse($localPart)
    {
        $parseDQuote = \true;
        $closingQuote = \false;
        $openedParenthesis = 0;
        while ($this->lexer->token['type'] !== \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_AT && null !== $this->lexer->token['type']) {
            if ($this->lexer->token['type'] === \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_DOT && null === $this->lexer->getPrevious()['type']) {
                throw new \MailPoetVendor\Egulias\EmailValidator\Exception\DotAtStart();
            }
            $closingQuote = $this->checkDQUOTE($closingQuote);
            if ($closingQuote && $parseDQuote) {
                $parseDQuote = $this->parseDoubleQuote();
            }
            if ($this->lexer->token['type'] === \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_OPENPARENTHESIS) {
                $this->parseComments();
                $openedParenthesis += $this->getOpenedParenthesis();
            }
            if ($this->lexer->token['type'] === \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_CLOSEPARENTHESIS) {
                if ($openedParenthesis === 0) {
                    throw new \MailPoetVendor\Egulias\EmailValidator\Exception\UnopenedComment();
                } else {
                    $openedParenthesis--;
                }
            }
            $this->checkConsecutiveDots();
            if ($this->lexer->token['type'] === \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_DOT && $this->lexer->isNextToken(\MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_AT)) {
                throw new \MailPoetVendor\Egulias\EmailValidator\Exception\DotAtEnd();
            }
            $this->warnEscaping();
            $this->isInvalidToken($this->lexer->token, $closingQuote);
            if ($this->isFWS()) {
                $this->parseFWS();
            }
            $this->lexer->moveNext();
        }
        $prev = $this->lexer->getPrevious();
        if (\strlen($prev['value']) > \MailPoetVendor\Egulias\EmailValidator\Warning\LocalTooLong::LOCAL_PART_LENGTH) {
            $this->warnings[\MailPoetVendor\Egulias\EmailValidator\Warning\LocalTooLong::CODE] = new \MailPoetVendor\Egulias\EmailValidator\Warning\LocalTooLong();
        }
    }
    /**
     * @return bool
     */
    protected function parseDoubleQuote()
    {
        $parseAgain = \true;
        $special = array(\MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_CR => \true, \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_HTAB => \true, \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_LF => \true);
        $invalid = array(\MailPoetVendor\Egulias\EmailValidator\EmailLexer::C_NUL => \true, \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_HTAB => \true, \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_CR => \true, \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_LF => \true);
        $setSpecialsWarning = \true;
        $this->lexer->moveNext();
        while ($this->lexer->token['type'] !== \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_DQUOTE && null !== $this->lexer->token['type']) {
            $parseAgain = \false;
            if (isset($special[$this->lexer->token['type']]) && $setSpecialsWarning) {
                $this->warnings[\MailPoetVendor\Egulias\EmailValidator\Warning\CFWSWithFWS::CODE] = new \MailPoetVendor\Egulias\EmailValidator\Warning\CFWSWithFWS();
                $setSpecialsWarning = \false;
            }
            if ($this->lexer->token['type'] === \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_BACKSLASH && $this->lexer->isNextToken(\MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_DQUOTE)) {
                $this->lexer->moveNext();
            }
            $this->lexer->moveNext();
            if (!$this->escaped() && isset($invalid[$this->lexer->token['type']])) {
                throw new \MailPoetVendor\Egulias\EmailValidator\Exception\ExpectingATEXT();
            }
        }
        $prev = $this->lexer->getPrevious();
        if ($prev['type'] === \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_BACKSLASH) {
            if (!$this->checkDQUOTE(\false)) {
                throw new \MailPoetVendor\Egulias\EmailValidator\Exception\UnclosedQuotedString();
            }
        }
        if (!$this->lexer->isNextToken(\MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_AT) && $prev['type'] !== \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_BACKSLASH) {
            throw new \MailPoetVendor\Egulias\EmailValidator\Exception\ExpectingAT();
        }
        return $parseAgain;
    }
    /**
     * @param bool $closingQuote
     */
    protected function isInvalidToken(array $token, $closingQuote)
    {
        $forbidden = array(\MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_COMMA, \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_CLOSEBRACKET, \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_OPENBRACKET, \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_GREATERTHAN, \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_LOWERTHAN, \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_COLON, \MailPoetVendor\Egulias\EmailValidator\EmailLexer::S_SEMICOLON, \MailPoetVendor\Egulias\EmailValidator\EmailLexer::INVALID);
        if (\in_array($token['type'], $forbidden) && !$closingQuote) {
            throw new \MailPoetVendor\Egulias\EmailValidator\Exception\ExpectingATEXT();
        }
    }
}
