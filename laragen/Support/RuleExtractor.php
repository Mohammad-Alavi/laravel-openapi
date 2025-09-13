<?php

namespace MohammadAlavi\Laragen\Support;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Knuckles\Camel\Extraction\ExtractedEndpointData;
use Knuckles\Scribe\Extracting\MethodAstParser;
use Knuckles\Scribe\Extracting\Strategies\BodyParameters\GetFromFormRequest as GetFromFormRequestBase;
use Knuckles\Scribe\Extracting\Strategies\BodyParameters\GetFromInlineValidator;
use Knuckles\Scribe\Tools\Globals;

final class RuleExtractor extends GetFromFormRequestBase
{
    public function extractFrom(Route $route): array
    {
        return $this->normaliseRules([...$this->requestRules($route), ...$this->inlineRules($route)]);
    }

    private function requestRules(Route $route): array
    {
        $formRequest = $this->getFormRequestInstance($route);

        if (is_null($formRequest)) {
            return [];
        }

        return $this->getRouteValidationRules($formRequest);
    }

    public function getFormRequestInstance(Route $route): FormRequest|null
    {
        $endpointData = ExtractedEndpointData::fromRoute($route);
        $method = $endpointData->method;

        $formRequestReflectionClass = $this->getFormRequestReflectionClass($method);
        if (
            is_null($formRequestReflectionClass)
            || !$this->isFormRequestMeantForThisStrategy($formRequestReflectionClass)
        ) {
            return null;
        }

        $className = $formRequestReflectionClass->getName();

        if (Globals::$__instantiateFormRequestUsing) {
            $formRequest = call_user_func_array(Globals::$__instantiateFormRequestUsing, [$className, $route, $method]);
        } else {
            $formRequest = new $className();
        }

        // Set the route properly so it works for users who have code that checks for the route.
        /* @var FormRequest $formRequest */
        $formRequest->setRouteResolver(function () use ($formRequest, $route) {
            // Also need to bind the request to the route in case their code tries to inspect current request
            return $route->bind($formRequest);
        });
        $formRequest->server->set('REQUEST_METHOD', $route->methods()[0]);

        return $formRequest;
    }

    private function inlineRules(Route $route): array
    {
        $endpointData = ExtractedEndpointData::fromRoute($route);
        /** @var GetFromInlineValidator $inlineValidator */
        $inlineValidator = app(GetFromInlineValidator::class);
        if (!$endpointData->method instanceof \ReflectionMethod) {
            return [];
        }

        $methodAst = MethodAstParser::getMethodAst($endpointData->method);

        return $inlineValidator->lookForInlineValidationRules($methodAst)[0] ?? [];
    }
}
