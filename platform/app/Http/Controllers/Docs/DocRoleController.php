<?php

declare(strict_types=1);

namespace App\Http\Controllers\Docs;

use App\Application\Documentation\Actions\CreateDocRole;
use App\Application\Documentation\Actions\DeleteDocRole;
use App\Application\Documentation\Actions\UpdateDocRole;
use App\Application\Documentation\DTOs\CreateDocRoleData;
use App\Application\Documentation\DTOs\UpdateDocRoleData;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;

final class DocRoleController extends Controller
{
    public function store(CreateDocRoleData $data, Project $project): RedirectResponse
    {
        app(CreateDocRole::class)->execute($project->id, [
            'name' => $data->name,
            'scopes' => $data->scopes,
            'is_default' => $data->is_default,
        ]);

        return back()->with('success', 'Role created.');
    }

    public function update(UpdateDocRoleData $data, Project $project, int $docRole): RedirectResponse
    {
        $updateData = array_filter($data->toArray(), fn ($v) => $v !== null);
        app(UpdateDocRole::class)->execute($docRole, $updateData);

        return back()->with('success', 'Role updated.');
    }

    public function destroy(Project $project, int $docRole): RedirectResponse
    {
        app(DeleteDocRole::class)->execute($docRole);

        return back()->with('success', 'Role deleted.');
    }
}
