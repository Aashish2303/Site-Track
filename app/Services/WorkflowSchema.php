<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WorkflowSchema
{
    private ?bool $installed = null;

    public function ready(): bool
    {
        return $this->installed ??= Schema::hasTable('site_workflow_installations')
            && DB::table('site_workflow_installations')->where('version', 1)->exists();
    }
}
