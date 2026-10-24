<?php

namespace App\Models\Concerns;

use App\Services\ProjectContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait HasProjectParent
{
    private static function workspaceParentDefinition(string $table): array
    {
        return [
            'labour_workers' => ['lworker_tracker', 'labour_trackers', 'ltracker_id', 'ltracker_project'],
            'indent_items' => ['iitem_indent', 'indents', 'indent_id', 'indent_project'],
            'indent_quotations' => ['iquote_indent', 'indents', 'indent_id', 'indent_project'],
        ][$table];
    }

    protected static function bootHasProjectParent(): void
    {
        static::addGlobalScope('workspace_parent', function (Builder $query) {
            $context = app(ProjectContext::class);
            if (!$context->scoped || !$context->id()) { return; }
            [$foreign, $parent, $key, $project] = static::workspaceParentDefinition($query->getModel()->getTable());
            $query->whereIn($query->getModel()->qualifyColumn($foreign),
                DB::table($parent)->select($key)->where($project, $context->id()));
        });
        static::saving(function (Model $model) {
            $context = app(ProjectContext::class);
            if (!$context->scoped || !$context->id()) { return; }
            [$foreign, $parent, $key, $project] = static::workspaceParentDefinition($model->getTable());
            if (!DB::table($parent)->where($key, $model->$foreign)->where($project, $context->id())->exists()) {
                throw ValidationException::withMessages([$foreign => 'The parent record belongs to another project.']);
            }
        });
    }
}
