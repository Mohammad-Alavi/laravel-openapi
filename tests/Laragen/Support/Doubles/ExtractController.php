<?php

namespace Tests\Laragen\Support\Doubles;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class ExtractController
{
    use ValidatesRequests;

    public function simpleRules(SimpleRulesFormRequest $request)
    {
    }

    public function formFormRequest(BodyFormRequest $request)
    {
    }

    public function fromInlineRequestValidateMethod(BodyFormRequest $request)
    {
        $request->validate([
            'address' => ['string', 'nullable', 'min:10'],
            'bar' => ['array', 'nullable', 'min:1'],
        ]);
    }

    public function fromInlineRequestFacade()
    {
        Request::validate([
            'content' => 'string|required|min:100',
            'extra' => 'string',
        ]);
    }

    public function fromInlineValidatorFacadeVarAssignment(BodyFormRequest $request)
    {
        $doesntMatter = Validator::make($request->all(), [
            'title' => 'string|required|max:400',
            'author_display_name' => 'string',
        ]);
    }

    public function fromThisValidate()
    {
        $this->validate(request(), [
            'title' => 'string|nullable',
            'author_display_name' => 'string',
        ]);
    }

    public function fromInlineValidatorFacadeValidateMethod(BodyFormRequest $request)
    {
        Validator::make($request->all(), [
            'title' => 'string|required|max:400',
            'author_display_name' => 'string',
        ])->validate();
    }

    public function fromRequestAndInline(BodyFormRequest $request)
    {
        Request::validate([
            'address' => ['string', 'nullable', 'min:10'],
            'bar' => ['array', 'nullable', 'min:1'],
        ]);
    }
}
