<?php

// function used to return current month dates
function getMonthDays($date) {
    $num_of_days = date('t', strtotime($date));
    for ($i = 1; $i <= $num_of_days; $i++) {
        $dates[] = date('Y', strtotime($date)) . "-" . date('m', strtotime($date)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
    }

    return $dates;
}

// function used to list dates between two dates
function listDates($strDateFrom, $strDateTo) {
    $aryRange = array();

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo) {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}

// function used to list months between two dates
function listMonths($strStart, $strEnd) {
    $start = (new DateTime($strStart))->modify('first day of this month');
    $end = (new DateTime($strEnd))->modify('first day of this month');
    $interval = DateInterval::createFromDateString('1 month');
    $period = new DatePeriod($start, $interval, $end);

    $months = array();
    foreach ($period as $dt) {
        array_push($months, $dt->format("Y-m"));
    }
    
    // add month of end date
    array_push($months, date("Y-m", strtotime($strEnd)));
    
    return $months;
}

?>