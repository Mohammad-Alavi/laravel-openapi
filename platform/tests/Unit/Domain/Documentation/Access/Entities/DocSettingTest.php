<?php

use App\Domain\Documentation\Access\Entities\DocSetting;
use App\Domain\Documentation\Access\Enums\DocVisibility;

describe(class_basename(DocSetting::class), function (): void {
    it('reports public visibility', function (): void {
        $setting = new DocSetting();
        $setting->forceFill([
            'project_id' => 1,
            'visibility' => DocVisibility::Public,
        ]);

        expect($setting->isPublic())->toBeTrue()
            ->and($setting->isPrivate())->toBeFalse()
            ->and($setting->getVisibility())->toBe(DocVisibility::Public);
    });

    it('reports private visibility', function (): void {
        $setting = new DocSetting();
        $setting->forceFill([
            'project_id' => 1,
            'visibility' => DocVisibility::Private,
        ]);

        expect($setting->isPublic())->toBeFalse()
            ->and($setting->isPrivate())->toBeTrue()
            ->and($setting->getVisibility())->toBe(DocVisibility::Private);
    });

    it('exposes project id', function (): void {
        $setting = new DocSetting();
        $setting->forceFill(['project_id' => 42, 'visibility' => DocVisibility::Public]);

        expect($setting->getProjectId())->toBe(42);
    });
})->covers(DocSetting::class);
