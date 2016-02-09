<?php

class GodController extends BaseController {

    public function getLogs() {
        $logs = AppLog::orderBy('datetime', 'DESC') -> get();

        return View::make('pages.admin.logs') -> with('logs', $logs);
    }

    public function postLogs(){
        $from = Input::has('from') ? Input::get('from') : date("Y-m-d");
        $to   = Input::has('to') ? new DateTime(Input::get('to')) : new DateTime(date("Y-m-d"));

        $to = date_time_set($to, 23, 59 ,59);
        $to = $to -> format('Y-m-d H:i:s');
        
        $logs = AppLog::getLogByDate($from, $to);

        return View::make('pages.admin.logs') -> with('logs', $logs)
                                              -> with('dStart', $from)
                                              -> with('dEnd', $to);
    }

}
