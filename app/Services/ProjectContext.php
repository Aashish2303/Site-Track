<?php

namespace App\Services;

use App\Models\Project;

class ProjectContext
{
    public ?Project $project = null;
    public bool $scoped = false;
    public bool $workspace = false;

    public function id(): ?int
    {
        return $this->project ? (int) $this->project->project_id : null;
    }
}
