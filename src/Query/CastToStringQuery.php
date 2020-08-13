<?php


namespace KejawenLab\Semart\Skeleton\Query;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class CastToStringQuery extends FunctionNode
{
    public $stringPrimary;
    public $type = 'text';

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'CAST(' . $this->stringPrimary->dispatch($sqlWalker) . ' AS '. $this->type .')';
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->stringPrimary = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

}
