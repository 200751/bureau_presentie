<?php
class Calendar {

    private $active_year, $active_month, $active_day, $std_nr;
    private $events = [];

    public function __construct($date = null, $std_nr = null) {
        $this->std_nr = $std_nr;
        if(date('m', strtotime($date)) == date('m') && date('Y', strtotime($date)) == date('Y')){
            $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
            $this->active_month = ($date != null)? date('m', strtotime($date)) : date('m');
            $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
        }else{
            $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
            $this->active_month = ($date != null)? date('m', strtotime($date)) : date('m');
            $this->active_day = 0;
        }
    }

    public function add_event($txt, $date, $days = 1, $color = '') {
        $color = $color ? ' ' . $color : $color;
        $this->events[] = [$txt, $date, $days, $color];
    }

    public function __toString() {
        $num_days = date('t', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year));
        $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));
        $days = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);
        
        $next_month = $this->active_month + 1;
        $next_year = $this->active_year;
        if($next_month >= 13 ){
            $next_month = 1;
            $next_year = $this->active_year + 1;
        }
        $next_link = $next_year.'-'.$next_month;
        if($next_month == date('m')){
            $next_day = date('d');
            $next_link = $next_year.'-'.$next_month.'-'.$next_day;
        }
        

        $prev_month = $this->active_month - 1;
        $prev_year = $this->active_year;
        if($prev_month <= 0 ){
            $prev_month = 12;
            $prev_year = $this->active_year - 1;
        }
        $prev_link = $prev_year.'-'.$prev_month;
        if($prev_month == date('m')){
            $prev_day = date('d');
            $prev_link = $prev_year.'-'.$prev_month.'-'.$prev_day;
        }
        
        $html = '<div class="calendar">';
        $html .= '<div class="header">';
        $html .= '<div class="month-year">';
        $html .= '<a href="user.php?user='.$this->std_nr.'&date=';
        $html .= $prev_link;
        $html .= '"> <i class="material-icons opacity-10">chevron_left</i> </a>&nbsp;&nbsp;&nbsp;';
        $html .= date('F Y', strtotime($this->active_year . '-' . $this->active_month));
        $html .= '&nbsp;&nbsp;&nbsp;<a href="user.php?user='.$this->std_nr.'&date=';
        $html .= $next_link;
        $html .= '"><i class="material-icons opacity-10">chevron_right</i></a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="days">';
        foreach ($days as $day) {
            $html .= '
                <div class="day_name">
                    ' . $day . '
                </div>
            ';
        }
        for ($i = $first_day_of_week; $i > 0; $i--) {
            $html .= '
                <div class="day_num ignore">
                    ' . ($num_days_last_month-$i+1) . '
                </div>
            ';
        }
        for ($i = 1; $i <= $num_days; $i++) {
            $selected = '';
            if ($i == $this->active_day) {
                $selected = ' selected';
            }
            $html .= '<div class="day_num' . $selected . '">';
            $html .= '<span>' . $i . '</span>';

            $html .= $this->getUserInfo($this->active_year."-".$this->active_month."-".$i);

            foreach ($this->events as $event) {
                for ($d = 0; $d <= ($event[2]-1); $d++) {
                    if (date('y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[1]))) {
                        $html .= '<div class="event' . $event[3] . '">';
                        $html .= $event[0];
                        $html .= '</div>';
                    }
                }
            }
            $html .= '</div>';
        }
        for ($i = 1; $i <= (42-$num_days-max($first_day_of_week, 0)); $i++) {
            $html .= '
                <div class="day_num ignore">
                    ' . $i . '
                </div>
            ';
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    public function getUserInfo($date) {
        global $con;
        
        list($check_time_qry_in) = mysqli_fetch_array($con->query("SELECT 
        GROUP_CONCAT(DATE_FORMAT(check_time,'%d-%m-%Y | %H:%i'),'|',check_type)
        FROM presentie WHERE AES_DECRYPT(presentie.check_number,'{$_ENV['SALT']}') = '{$this->std_nr}' AND check_type = 'in' AND check_date = '{$date}' ;"));

        list($check_time_qry_out) = mysqli_fetch_array($con->query("SELECT 
        GROUP_CONCAT(DATE_FORMAT(check_time,'%d-%m-%Y | %H:%i'),'|',check_type)
        FROM presentie WHERE AES_DECRYPT(presentie.check_number,'{$_ENV['SALT']}') = '{$this->std_nr}' AND check_type = 'out' AND check_date = '{$date}' ;"));


        $checkedIn = false;
        $checkedOut = false;
        if($check_time_qry_in != ''){
            $check_time_in = explode(',',$check_time_qry_in);
            if(!empty($check_time_in)){
                $check_type = explode('|',$check_time_in[0]);
                $check_in = $check_type;
                $rowClass = "blue";
                $checkedIn = true;
            }
        }else{
            $rowClass = "red";
        }

        if($check_time_qry_out != ''){
            $check_time_out = explode(',',$check_time_qry_out);
            if(!empty($check_time_out)){
                $check_type = explode('|',$check_time_out[0]);
                $check_out = $check_type;
                $rowClass = "blue";
                $checkedOut = true;
            }
        }

        if($checkedIn && $checkedOut){
            $rowClass = "green";
        }
        $time_in = (!empty($check_in)) ? $check_in[1] : '';
        $time_out = (!empty($check_out)) ? $check_out[1] : '';
        return "<div class='event {$rowClass}'>checkin: {$time_in} <br> checkout: {$time_out}</div>";



        $liqry->close();

    }

}
?>
