<?php

namespace Tests\Laragen\Support\Doubles\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWithMerge extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            $this->merge([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
            ]),
            $this->mergeWhen($this->is_admin, [
                'role' => 'admin',
                'permissions' => $this->permissions,
            ]),
            $this->mergeUnless($this->is_guest, [
                'settings' => $this->settings,
            ]),
        ];
    }
}
