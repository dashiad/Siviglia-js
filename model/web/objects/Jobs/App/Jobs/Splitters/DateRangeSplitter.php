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
        
        $startDate = explode(" ", $params['start_date']);
        $endDate = explode(" ", $params['end_date']);

        $startDateStr = $startDate[0]." ";
        $endDateStr = $endDate[0]." ";
        
        $startDateStr .= $startDate[1] ?? "00:00:00";
        $endDateStr .= $endDate[1] ?? "23:59:59";
        
        $period = new \DatePeriod(
            new \DateTime($startDateStr),
            new \DateInterval($this->getInterval($period, $interval)),
            new \DateTime($endDateStr)
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