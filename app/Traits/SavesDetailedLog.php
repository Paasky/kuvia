<?php

namespace App\Traits;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;

trait SavesDetailedLog
{
    /**
     * @param string $activity
     * @param Model $performedOn
     * @param Model $causedBy
     * @param string$logName
     */
    public function saveDetailedLog(string $activity, $performedOn = null, $causedBy = null, string $logName = 'default') : void
    {
        $log = activity($logName)->withProperties([
            'user-agent' => \Request::userAgent(),
            'ip' => \Request::ip() // todo if behind load balancer: \Request::header('X-Forwarded-For')
        ]);

        if ($performedOn) {
            $log->performedOn($performedOn);
        }
        if ($causedBy = $causedBy ?: UserRepository::getLoggedInUser()) {
            $log->causedBy($causedBy);
        }

        $log->log($activity);
    }
}
