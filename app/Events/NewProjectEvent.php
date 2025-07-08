<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Foundation\Events\Dispatchable;

class NewProjectEvent
{
use Dispatchable;

public function __construct(public Project $project)
{
}
}
