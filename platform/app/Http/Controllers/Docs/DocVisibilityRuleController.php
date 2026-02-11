<?php

declare(strict_types=1);

namespace App\Http\Controllers\Docs;

use App\Application\Documentation\Actions\CreateVisibilityRule;
use App\Application\Documentation\Actions\DeleteVisibilityRule;
use App\Application\Documentation\Actions\UpdateVisibilityRule;
use App\Application\Documentation\DTOs\CreateVisibilityRuleData;
use App\Application\Documentation\DTOs\UpdateVisibilityRuleData;
use App\Domain\Documentation\Access\Entities\DocVisibilityRule;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;

final class DocVisibilityRuleController extends Controller
{
    public function store(CreateVisibilityRuleData $data, Project $project): RedirectResponse
    {
        app(CreateVisibilityRule::class)->execute([
            'project_id' => $project->id,
            'rule_type' => $data->rule_type->value,
            'identifier' => $data->identifier,
            'visibility' => $data->visibility->value,
        ]);

        return back()->with('success', 'Visibility rule created.');
    }

    public function update(UpdateVisibilityRuleData $data, Project $project, DocVisibilityRule $docRule): RedirectResponse
    {
        $updateData = collect($data->toArray())
            ->filter(fn ($v) => $v !== null)
            ->map(fn ($v) => $v instanceof \BackedEnum ? $v->value : $v)
            ->all();

        app(UpdateVisibilityRule::class)->execute($docRule->getUlid(), $updateData);

        return back()->with('success', 'Visibility rule updated.');
    }

    public function destroy(Project $project, DocVisibilityRule $docRule): RedirectResponse
    {
        app(DeleteVisibilityRule::class)->execute($docRule->getUlid());

        return back()->with('success', 'Visibility rule deleted.');
    }
}
