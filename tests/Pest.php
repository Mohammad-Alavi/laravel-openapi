<?php

use Pest\Expectation;
use Tests\IntegrationTestCase;
use Tests\UnitTestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

pest()->extends(IntegrationTestCase::class)->in(
    'src/Integration',
    'oooas/Integration',
    'JSONSchema/Integration',
)->afterEach(fn () => cleanup($this->cleanupCallbacks));
pest()->extends(UnitTestCase::class)->in(
    'src/Unit',
    'oooas/Unit',
    'JSONSchema/Unit',
)->afterEach(fn () => cleanup($this->cleanupCallbacks));

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', fn () => $this->toBe(1));
expect()->extend('toBeImmutable', function (): void {
    $reflection = new ReflectionClass($this->value);

    expect($reflection->isReadOnly())->toBeTrue(
        'The class ' . $this->value . ' is not immutable.',
    );
});
expect()->extend(
    'toBeValidJsonSchema',
    function (): void {
        exec(
            "npx redocly lint --format stylish --extends recommended-strict $this->value 2>&1",
            $output,
            $result_code,
        );
        $this->when(
            $result_code,
            function (Expectation $expectation) use ($output): Expectation {
                return $expectation->toBeEmpty(implode("\n", $output));
            },
        );
    },
);

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function cleanup(array $callbacks): void
{
    foreach ($callbacks as $callback) {
        $callback();
    }
}
