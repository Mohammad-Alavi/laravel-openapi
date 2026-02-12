<?php

namespace App\Http\Controllers\Docs;

use App\Application\Documentation\Actions\CreateAccessLink;
use App\Application\Documentation\Actions\RevokeAccessLink;
use App\Application\Documentation\DTOs\CreateAccessLinkData;
use App\Domain\Documentation\Access\Entities\DocAccessLink;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;

final class DocAccessLinkController extends Controller
{
    public function store(CreateAccessLinkData $data, Project $project, CreateAccessLink $action): RedirectResponse
    {
        $result = $action->execute(
            $project->id,
            $data->doc_role_id,
            $data->name,
            $data->expires_at,
        );

        return back()->with([
            'success' => 'Access link created.',
            'plain_token' => $result->token->toString(),
        ]);
    }

    public function destroy(Project $project, DocAccessLink $docLink, RevokeAccessLink $action): RedirectResponse
    {
        $action->execute($docLink->getUlid());

        return back()->with('success', 'Access link revoked.');
    }
}
