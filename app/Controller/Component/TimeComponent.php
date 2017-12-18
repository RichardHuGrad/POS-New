<?php

App::uses('Component', 'Controller');

class TimeComponent extends Component {
	
    public function getTimelineArray($type,$from_date='',$to_date='') {
    	
        date_default_timezone_set("America/Toronto");
        
        $date_time = date("l M d Y h:i:s A");
        $timeline = strtotime(date("Y-m-d 10:00:00"));
        $nowtm = time();
        if ($timeline > $nowtm) {
            // before 11 am
            $timeline -= 86400;  //one day before
        }

        if ($type == "today") {
        	
            // $timeline = $this->getTimeline();
            $tm11 = $timeline;
            $tm17 = $timeline + 3600 * 6;    // 6  hours
            $tm23 = $timeline + 3600 * 12;   // 12 hours
            //$tm04 = $timeline + 3600 * 17;
            $tm04 = $timeline + 86400 - 60;   //from 10:00 to second day 09:59

            return array($tm11, $tm17, $tm23, $tm04);
            
        } else if ($type == "yesterday") {
            $timeline -= 86400;
            $tm11 = $timeline;
            $tm17 = $timeline + 3600 * 6;
            $tm23 = $timeline + 3600 * 12;
            //$tm04 = $timeline + 3600 * 17;
            $tm04 = $timeline + 86400 - 60;   //from 10:00 to second day 09:59

            return array($tm11, $tm17, $tm23, $tm04);
            
        } else if ($type == "month") {

            $date_time = date("l M d Y h:i:s A");
            $timeline = strtotime(date("Y-m-01 10:00:00"));
            $nowtm = time();

            return array($timeline, $nowtm);
            
        } else if ($type == "period") {
        	
            $from_date = strtotime(date($from_date." 10:00:00")); 
            $to_date   = strtotime($to_date." 09:59:00" ."+1 day"); 

            return array($from_date, $to_date);
        }
    }

    public static function verifyRequiredParams($args, $required_fields) {
        $error = false;
        $error_fields = "";

        foreach ($required_fields as $field) {
            if (!isset($args[$field]) || strlen(trim($args[$field])) <= 0) {
                $error = true;
                $error_fields .= $field . ', ';
                throw new Exception('Missing argument: ' . $field);
            }
        }

        return !$error;
    }

}

 ?>
