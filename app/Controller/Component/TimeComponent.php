<?php

App::uses('Component', 'Controller');

class TimeComponent extends Component {
    public function getTimelineArray($type) {
        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");
        $timeline = strtotime(date("Y-m-d 11:00:00"));
        $nowtm = time();
        if ($timeline > $nowtm) {
            // before 11 am
            $timeline -= 86400;
        }

        if ($type == "today") {
            // $timeline = $this->getTimeline();
            $tm11 = $timeline;
            $tm17 = $timeline + 3600 * 6;
            $tm23 = $timeline + 3600 * 12;
            $tm04 = $timeline + 3600 * 17;

            return array($tm11, $tm17, $tm23, $tm04);
        } else if ($type == "yesterday") {
            $timeline -= 86400;
            $tm11 = $timeline;
            $tm17 = $timeline + 3600 * 6;
            $tm23 = $timeline + 3600 * 12;
            $tm04 = $timeline + 3600 * 17;

            return array($tm11, $tm17, $tm23, $tm04);
        } else if ($type == "month") {
            date_default_timezone_set("America/Toronto");
            $date_time = date("l M d Y h:i:s A");
            $timeline = strtotime(date("Y-m-01 11:00:00"));
            $nowtm = time();

            return array($timeline, $nowtm);
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
