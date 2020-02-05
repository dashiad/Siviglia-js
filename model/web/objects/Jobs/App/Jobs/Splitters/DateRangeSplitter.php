<?php
namespace model\web\Jobs\App\Jobs\Splitters;

class DateRangeSplitter implements SplitterInterface
{

    public function get(Array $params) : Array
    {
        $items = $this->createRange($params);
        return array_chunk($items, $params['max_chunk_size']);
    }
    
    public function createRange($params) : Array
    {
        $datesArray = [];
        $period = (isset($params['granularity'])) ? $params['granularity'] : 'DAY';
        $interval = (isset($params['interval'])) ? $params['interval'] : 1;
        
        $period = new \DatePeriod(
            new \DateTime($params['start_date']),
            new \DateInterval($this->getInterval($period, $interval)),
            new \DateTime($params['end_date'])
            );
        foreach($period as $date) {
            $datesArray[] = $date->format('Y-m-d H:i:s');
        }
        return $datesArray;
    }
    
    private function getInterval($period, $interval) 
    {
        $periodDescription = "P";
        switch ($period) {
            case 'YEAR':
                $periodDescription.=$interval."Y";
                break;
            case 'MONTH':
                $periodDescription.=$interval."M";
                break;
            case 'DAY':
                $periodDescription.=$interval."D";
                break;
            case 'HOUR':
                $periodDescription.="T".$interval."H";
                break;
            case 'MINUTE':
                $periodDescription.="T".$interval."M";
                break;
            case 'SECOND':
                $periodDescription.="T".$interval."S";
                break;
            default:
                $periodDescription.=$interval."D";
        }
        return $periodDescription;
    }

}