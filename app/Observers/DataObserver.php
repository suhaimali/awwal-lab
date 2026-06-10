<?php

namespace App\Observers;

use App\Events\DataChanged;

class DataObserver
{
    public function saved($model)
    {
        // event(new DataChanged(get_class($model), 'saved'));
    }

    public function deleted($model)
    {
        // event(new DataChanged(get_class($model), 'deleted'));
    }
}

