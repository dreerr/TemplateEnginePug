<?php

/**
 * @example <span>Raw html</span>
 */

namespace Phug\Lexer\Scanner;

use Phug\Lexer\Analyzer\LineAnalyzer;
use Phug\Lexer\State;
use Phug\Lexer\Token\NewLineToken;

class MarkupScanner extends MultilineScanner
{
    public function scan(State $state)
    {
        $reader = $state->getReader();

        if (!$reader->peekChar('<')) {
            return;
        }

        $analyzer = new LineAnalyzer($state, $reader);
        $analyzer->analyze(false, ['<']);
        $lines = $analyzer->getLines();

        foreach ($this->getUnescapedLines($state, $lines) as $token) {
            yield $token;
        }

        if ($analyzer->hasNewLine()) {
            yield $state->createToken(NewLineToken::class);
        }
    }
}
