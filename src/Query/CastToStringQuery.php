<?php


namespace KejawenLab\Semart\Skeleton\Query;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

final class CastToStringQuery extends FunctionNode
{
    private const TYPE = 'text';
    private $stringPrimary;

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('CAST(%s AS %s)', $this->stringPrimary->dispatch($sqlWalker), self::TYPE);
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->stringPrimary = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

}
