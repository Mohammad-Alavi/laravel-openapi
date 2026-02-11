<?php

declare(strict_types=1);

namespace App\Http\Controllers\Docs;

use App\Application\Documentation\Actions\UpdateDocSetting;
use App\Application\Documentation\DTOs\UpdateDocSettingData;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;

final class DocSettingController extends Controller
{
    public function update(UpdateDocSettingData $data, Project $project, UpdateDocSetting $action): RedirectResponse
    {
        $action->execute($project->id, $data->visibility);

        return back()->with('success', 'Documentation visibility updated.');
    }
}
