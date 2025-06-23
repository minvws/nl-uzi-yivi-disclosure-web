<?php

declare(strict_types=1);

namespace Tests;

use App\Models\UziRelation;
use App\Models\UziRelationRole;
use App\Models\UziUser;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

/**
 * Class TestCase.
 *
 * @package Tests
 */
abstract class TestCase extends BaseTestCase
{
    protected function getMockUziUser(
        string $initials = 'A',
        string $surname = 'Test',
        string $surnamePrefix = 'van',
        string $uziId = '123',
        string $loaAuthn = 'high',
        string $loaUzi = 'medium',
        array $uras = [],
    ): UziUser {
        return new UziUser(
            initials: $initials,
            surname: $surname,
            surnamePrefix: $surnamePrefix,
            uziId: $uziId,
            loaAuthn: $loaAuthn,
            loaUzi: $loaUzi,
            uras: $uras
        );
    }

    protected function getMockUziRelation(
        string $entityName = 'TestEntity',
        string $ura = '456',
        array $roles = []
    ): UziRelation {
        return new UziRelation(
            entityName: $entityName,
            ura: $ura,
            roles: $roles
        );
    }

    protected function getMockUziRole(string $name = 'Role One'): UziRelationRole
    {
        return new UziRelationRole(
            code: Str::slug($name),
            name: $name,
        );
    }

    protected function getMockUziUserWithRelations(): UziUser
    {
        $role = $this->getMockUziRole();

        $ura = $this->getMockUziRelation(
            roles: [$role]
        );

        return $this->getMockUziUser(
            uras: [$ura]
        );
    }
}
