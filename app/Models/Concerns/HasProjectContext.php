<?php

namespace App\Models\Concerns;

use App\Services\ProjectContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

trait HasProjectContext
{
    public static function projectColumn(): string
    {
        return [
            'projects' => 'project_id', 'project_boqs' => 'pboq_project',
            'daily_works' => 'dwork_project', 'daily_progress' => 'dprogress_project',
            'assign_materials' => 'amaterial_project', 'return_materials' => 'rmaterial_project',
            'labour_trackers' => 'ltracker_project', 'issues' => 'issue_project',
            'indents' => 'indent_project',
        ][(new static)->getTable()];
    }

    protected static function bootHasProjectContext(): void
    {
        static::addGlobalScope('workspace_project', function (Builder $query) {
            $context = app(ProjectContext::class);
            if ($context->scoped && $context->id()) {
                $query->where($query->getModel()->qualifyColumn(static::projectColumn()), $context->id());
            }
        });

        static::saving(function (Model $model) {
            $context = app(ProjectContext::class);
            $column = static::projectColumn();
            if ($context->scoped && $context->id() && (int) $model->$column !== $context->id()) {
                throw ValidationException::withMessages([$column => 'This record belongs to another project.']);
            }
        });
    }
}
