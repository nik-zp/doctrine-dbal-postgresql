<?php

declare(strict_types=1);

namespace Opsway\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

use function sprintf;

class ToChar extends FunctionNode
{
    public mixed $dateExpression;
    public mixed $patternExpression;

    public function parse(Parser $parser) : void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->dateExpression = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->patternExpression = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker) : string
    {
        return sprintf(
            'TO_CHAR(%s, %s)',
            $this->dateExpression->dispatch($sqlWalker),
            $this->patternExpression->dispatch($sqlWalker)
        );
    }
}
