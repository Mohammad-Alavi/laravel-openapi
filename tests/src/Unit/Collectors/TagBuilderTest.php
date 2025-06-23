<?php

namespace Tests\src\Unit\Collectors;

use MohammadAlavi\LaravelOpenApi\Builders\TagBuilder;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\TagFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\src\Support\Doubles\Stubs\Tags\TagWithExternalObjectDoc;
use Tests\src\Support\Doubles\Stubs\Tags\TagWithoutExternalDoc;
use Tests\TestCase;

#[CoversClass(TagBuilder::class)]
class TagBuilderTest extends TestCase
{
    public static function singleTagProvider(): \Iterator
    {
        yield 'Can build tag from array with one FQCN' => [
            [TagWithoutExternalDoc::class],
            [
                [
                    'name' => 'PostWithoutExternalDoc',
                    'description' => 'Post Tag',
                ],
            ],
        ];
        yield 'Can build tag without external docs' => [
            [TagWithoutExternalDoc::class],
            [
                [
                    'name' => 'PostWithoutExternalDoc',
                    'description' => 'Post Tag',
                ],
            ],
        ];
        yield 'Can build tag with external docs' => [
            [TagWithExternalObjectDoc::class],
            [
                [
                    'name' => 'PostWithExternalObjectDoc',
                    'description' => 'Post Tag',
                    'externalDocs' => [
                        'url' => 'https://example.com/external-docs',
                        'description' => 'External API documentation',
                    ],
                ],
            ],
        ];
    }

    public static function multiTagProvider(): \Iterator
    {
        yield 'Can build multiple tags from an array of FQCNs' => [
            [TagWithoutExternalDoc::class, TagWithExternalObjectDoc::class],
            [
                [
                    'name' => 'PostWithoutExternalDoc',
                    'description' => 'Post Tag',
                ],
                [
                    'name' => 'PostWithExternalObjectDoc',
                    'description' => 'Post Tag',
                    'externalDocs' => [
                        'url' => 'https://example.com/external-docs',
                        'description' => 'External API documentation',
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('singleTagProvider')]
    public function testCanBuildTag(array $factories, array $expected): void
    {
        $tagBuilder = app(TagBuilder::class);
        $tags = $tagBuilder->build($factories);

        $this->assertSameAssociativeArray($expected[0], $tags[0]->asArray());
    }

    /**
     * Assert equality as an associative array.
     */
    protected function assertSameAssociativeArray(array $expected, array $actual): void
    {
        foreach ($expected as $key => $value) {
            if (is_array($value)) {
                $this->assertSameAssociativeArray($value, $actual[$key]);
                unset($actual[$key]);
                continue;
            }

            $this->assertSame($value, $actual[$key]);
            unset($actual[$key]);
        }

        $this->assertCount(
            0,
            $actual,
            sprintf('[%s] does not matched keys.', implode(', ', array_keys($actual))),
        );
    }

    #[DataProvider('multiTagProvider')]
    public function testCanBuildFromTagArray(array $factories, array $expected): void
    {
        $tagBuilder = app(TagBuilder::class);
        $tags = $tagBuilder->build($factories);

        $this->assertSame(
            $expected,
            collect($tags)
                ->map(static fn (Tag $tag): array => $tag->asArray())
                ->toArray(),
        );
    }

    public function testGivenInvalidNameProvidedCanProduceCorrectException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $tagBuilder = app(TagBuilder::class);
        $tagBuilder->build([(new class implements TagFactory {
            public function build(): Tag
            {
                return Tag::create(
                    Name::create(''),
                    Description::create('Post Tag'),
                );
            }
        })::class]);
    }
}
