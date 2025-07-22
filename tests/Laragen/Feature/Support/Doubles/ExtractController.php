<?php

namespace Tests\Laragen\Feature\Support\Doubles;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Workbench\App\Models\User;

class ExtractController
{
    public function formRequest(BodyFormRequest $request)
    {
    }

    public function fromInlineRequestFacade()
    {
        Request::validate([
            'content' => 'string|required|min:100',
            'extra' => 'string',
        ]);
    }

    public function fromInlineRequestValidateMethod(Request $request)
    {
        $request->validate([
            'address' => ['string', 'nullable', 'min:10'],
            'bar' => ['array', 'nullable', 'min:1'],
        ]);
    }

    public function fromInlineValidatorFacade(BodyFormRequest $request)
    {
        $doesntMatter = Validator::make($request->all(), [
            'title' => 'string|required|max:400',
            'author_display_name' => 'string',
        ]);
    }

    public function fromRequestAndInline(BodyFormRequest $request)
    {
        Request::validate([
            'address' => ['string', 'nullable', 'min:10'],
            'bar' => ['array', 'nullable', 'min:1'],
        ]);
    }
}