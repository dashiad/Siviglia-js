<?php
namespace model\web\Jobs\App\Jobs;

use model\web\Jobs\App\Jobs\Splitters\SplitterInterface;
use model\web\Jobs\App\Jobs\Splitters\ListSplitter;
use model\web\Jobs\App\Jobs\Splitters\DateRangeSplitter;

/**
 * 
 * Fachada para splitters de tipos de datos
 * 
 * @author Smartclip
 *
 */
class Split
{
    public static function get(Array $args)
    {   
        $type   = $args['type'];
        $params = $args['params'];
        
        $splitter = self::getSplitter($type);
        return $splitter->get($params);        
    }
    
    private static function getSplitter(String $type) : SplitterInterface 
    {
        /*switch ($type) {
            case 'List':
                return new ListSplitter();
                break;
            case 'DateRange':
                return new DateRangeSplitter();
                break;
            default:
                throw new \Exception("No existe un splitter para el foramto $type");
        }*/
        $className = JOBS_NAMESPACE."Splitters\\".$type."Splitter";
        return new $className;
    }
    
}

