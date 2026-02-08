<?php

namespace Tests\Laragen\Support\Doubles\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWithConditionals extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tags' => $this->when($this->tags_visible, $this->tags),
            'profile' => $this->whenLoaded('profile'),
            'role' => $this->unless($this->is_guest, $this->role),
            'has_avatar' => $this->whenHas('avatar'),
            'nickname' => $this->whenNotNull($this->nickname),
            'bio' => $this->whenNull($this->bio),
            'display_name' => $this->whenAppended('display_name'),
            'comments_count' => $this->whenCounted('comments'),
            'avg_rating' => $this->whenAggregated('reviews', 'rating', 'avg'),
            'exists_check' => $this->whenExistsLoaded('membership'),
            'pivot_role' => $this->whenPivotLoaded('role_user', function () {
                return $this->pivot->role;
            }),
            'pivot_as' => $this->whenPivotLoadedAs('membership', 'role_user', function () {
                return $this->membership->role;
            }),
        ];
    }
}
