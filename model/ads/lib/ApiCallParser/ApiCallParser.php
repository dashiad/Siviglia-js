<?php
namespace model\ads\lib;

use Parle\Token;
use Parle\Lexer;
use Parle\Parser;
use Parle\ParserException;

class ApiCallParser {

    const TOKENS = [
        "COMMA",
        "CRLF",
        "NUMBER",
        "IDENTIFIER",
        "TEXT",
        "OPERATOR",
        "EQ_OPERATOR",
        "PAR_OPEN",
        "PAR_CLOSE",
        "QUOTE_SIMPLE",
        "QUOTE_DOBLE",
        "ARR_OPEN",
        "ARR_CLOSE",
        "EXIT",
    ];

    protected $parser;
    protected $lex;
    protected $query;
    protected $params;
    protected $fields;
    protected $result;

    public function __construct()
    {
        $this->parser = new Parser;
        $this->initParser();
    }

    protected function initParser()
    {
        foreach (static::TOKENS as $tokenId) {
            $this->parser->token($tokenId);
        }
        $this->addParserRules();
        $this->initLex();
    }

    protected function addParserRules()
    {
        // Main syntax
        $this->parser->push("START", "QUERY");
        $this->exit = $this->parser->push("START", "EXIT");
        $this->query = $this->parser->push("QUERY", "'on' IDENTIFIER 'call' IDENTIFIER PARAMS RETURN");
        
        // Param syntax
        $this->parser->push("PARAMS", "");
        $this->parser->push("PARAMS", "'with' PAR_OPEN PARAM_LIST PAR_CLOSE");
        $this->parser->push("PARAM_LIST", "PARAM");
        $this->parser->push("PARAM_LIST", "PARAM COMMA");
        $this->parser->push("PARAM_LIST", "PARAM COMMA PARAM_LIST");
        $this->textParam = $this->parser->push("PARAM", "IDENTIFIER OPERATOR TEXT");
        $this->numberParam = $this->parser->push("PARAM", "IDENTIFIER OPERATOR NUMBER");
        $this->numberParam = $this->parser->push("PARAM", "IDENTIFIER OPERATOR ARRAY");
        
        $this->arrayParam = $this->parser->push("ARRAY", "ARR_OPEN ARR_LIST ARR_CLOSE");
        $this->parser->push("ARR_LIST", "TEXT");
        $this->parser->push("ARR_LIST", "TEXT COMMA");
        $this->parser->push("ARR_LIST", "TEXT COMMA ARR_LIST");
        $this->parser->push("ARR_LIST", "NUMBER");
        $this->parser->push("ARR_LIST", "NUMBER COMMA");
        $this->parser->push("ARR_LIST", "NUMBER ARR_LIST");
        
        // Return fields syntax
        $this->parser->push("RETURN", "");
        $this->parser->push("RETURN", "'return' 'all'");
        $this->parser->push("RETURN", "'return' PAR_OPEN FIELDS PAR_CLOSE");
        $this->parser->push("FIELDS", "FIELD");
        $this->parser->push("FIELDS", "FIELD COMMA FIELDS");
        $this->field = $this->parser->push("FIELD", "IDENTIFIER");
        
        // Build the parser
        $this->parser->build();
    }

    protected function initLex()
    {
        $this->lex = new Lexer;
        $this->lex->push("(?i:ON)", $this->parser->tokenId("'on'"));
        $this->lex->push("(?i:CALL)", $this->parser->tokenId("'call'"));
        $this->lex->push("(?i:WITH)", $this->parser->tokenId("'with'"));
        $this->lex->push("(?i:RETURN)", $this->parser->tokenId("'return'"));
        $this->lex->push("(?i:EXIT)", $this->parser->tokenId("EXIT"));
        $this->lex->push("[*]", $this->parser->tokenId("'all'"));
        $this->lex->push("[\x2c]", $this->parser->tokenId("COMMA"));
        $this->lex->push("[\r][\n]", $this->parser->tokenId("CRLF"));
        $this->lex->push("[\/]?[_]*[A-Za-z][A-Za-z_\-0-9\/]*", $this->parser->tokenId("IDENTIFIER"));
        $this->lex->push("[\d]+(.[\d]+)?", $this->parser->tokenId("NUMBER"));
        $this->lex->push("([\'][^\n]{-}[\']*[\'])|([\"][^\n]{-}[\"]*[\"])", $this->parser->tokenId("TEXT"));
        $this->lex->push("[=<>]", $this->parser->tokenId("OPERATOR"));
        $this->lex->push("[=]", $this->parser->tokenId("EQ_OPERATOR"));
        $this->lex->push("[\x28]", $this->parser->tokenId("PAR_OPEN"));
        $this->lex->push("[\x29]", $this->parser->tokenId("PAR_CLOSE"));
        $this->lex->push("[\[]", $this->parser->tokenId("PAR_OPEN"));
        $this->lex->push("[\]]", $this->parser->tokenId("PAR_CLOSE"));
        $this->lex->push("[\']", $this->parser->tokenId("QUOTE_SIMPLE"));
        $this->lex->push("[\"]", $this->parser->tokenId("QUOTE_DOBLE"));      
        
        $this->lex->push("\\s+", Token::SKIP);
        $this->lex->build();
    }

    protected function clearResults()
    {
        return $this->result = [
            'api'    => null,
            'call'   => null,
            'params' => [],
            'fields' => [],
        ];
    }

    public function parse(String $input) {
        $result = $this->clearResults();

        if (!$this->parser->validate($input, $this->lex)) {
            throw new ApiCallParserException(ApiCallParserException::ERR_SYNTAX_ERROR);
        }

        $this->parser->consume($input, $this->lex);

        while (Parser::ACTION_ERROR != $this->parser->action && Parser::ACTION_ACCEPT != $this->parser->action) {
            switch ($this->parser->action) {
        	case Parser::ACTION_ERROR:
        	    throw new ApiCallParserException(ApiCallParserException::ERR_PARSER_ERROR);
        	    break;
        	case Parser::ACTION_SHIFT:
        	case Parser::ACTION_GOTO:
        	    break;
        	case Parser::ACTION_REDUCE:
        	    $rid = $this->parser->reduceId;
        	    
        	    switch ($rid) {
        	        case $this->query:
        	            $result['api'] = $this->parser->sigil(1);
        	            $result['call'] = $this->parser->sigil(3);
        	            break;
        	        case $this->textParam:
        	            $result['params'][$this->parser->sigil(0)] = $this->getParam("string");
        	            break;
        	        case $this->numberParam:
        	            $result['params'][$this->parser->sigil(0)] = $this->getParam("number");
        	            break;
        	        case $this->field:
        	            array_push($result['fields'], $this->parser->sigil(0));
        	            break;
        	        case $this->exit:
        	            exit();
        	    }        	    
        	break;
            }
            $this->parser->advance();
        }
        return $this->result = $result;
    }
    
    protected function getParam(String $type)
    {

        if ($type=="string" && substr($this->parser->sigil(2), 1, 1)=="[") {
            $value = substr($this->parser->sigil(2), 1, -1);
        } else {
            $value = $this->parser->sigil(2);
        }
        eval("\$val=$value;");
        
        return [
            'type'  => $type,
            'name'  => $this->parser->sigil(0),
            'op'    => $this->parser->sigil(1),
            'value' => $val,
        ];
    }
    
    public function cli()
    {
        $filename = getenv('HOME')."/.parserhistory";
        readline_read_history($filename);
        while(true) {
            $cmd = readline("[querybuilder] > ");
            readline_add_history($cmd);
            readline_write_history($filename);
            try {
                echo json_encode($this->parse($cmd), JSON_PRETTY_PRINT).PHP_EOL;
            } catch (ParserException $e) {
                echo $e->getMessage().PHP_EOL;
            }
        }
    }    
}

class ApiCallParserException extends \lib\model\BaseException
{
    const ERR_SYNTAX_ERROR = 1;
    const ERR_PARSER_ERROR = 1;
}