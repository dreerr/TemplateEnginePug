<?php

/**
 * @example header
 */

namespace Phug\Lexer\Scanner;

use Phug\Lexer\ScannerInterface;
use Phug\Lexer\State;
use Phug\Lexer\Token\TagToken;

class TagScanner implements ScannerInterface
{
    const TOKEN_CLASS = TagToken::class;

    const PATTERN = '(?<name>[a-zA-Z_][a-zA-Z0-9_]*(?:[:-][a-zA-Z_][a-zA-Z0-9_]*)*)';

    public function scan(State $state)
    {
        foreach ($state->scanToken(static::TOKEN_CLASS, static::PATTERN, 'i') as $token) {
            yield $token;

            foreach ($state->scan(ClassScanner::class) as $subToken) {
                yield $subToken;
            }

            foreach ($state->scan(IdScanner::class) as $subToken) {
                yield $subToken;
            }

            foreach ($state->scan(AutoCloseScanner::class) as $subToken) {
                yield $subToken;
            }

            foreach ($state->scan(SubScanner::class) as $subToken) {
                yield $subToken;
            }
        }
    }
}
