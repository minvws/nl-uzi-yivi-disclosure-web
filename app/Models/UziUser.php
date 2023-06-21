<?php

declare(strict_types=1);

namespace App\Models;

use Jose\Easy\ParameterBag;
use Illuminate\Contracts\Auth\Authenticatable;
use RuntimeException;

class UziUser implements Authenticatable
{
    public function __construct(
        public string|null $initials,
        public string|null $surname,
        public string|null $surnamePrefix,
        public string $uziId,
        public string|null $loaAuthn,
        public string $loaUzi,
        public array $uras,
        public string $email = ''
    ) {
        if (empty($email)) {
            $this->email = $uziId . '@uzi.pas';
        }
    }

    public static function getFromParameterBag(ParameterBag $data): UziUser | null
    {
        $requiredKeys = ["relations", "initials", "surname", "surname_prefix", "uzi_id", "loa_uzi"];
        $missingKeys = [];
        foreach ($requiredKeys as $key) {
            if (!$data->has($key)) {
                $missingKeys[] = $key;
            }
        }
        if (count($missingKeys) > 0) {
            return null;
        }

        $relations = [];
        foreach ($data->get('relations') as $relation) {
            $relations[] = new UziRelation($relation['entity_name'], $relation['ura'], $relation['roles']);
        }
        return new self(
            initials: $data->get('initials'),
            surname: $data->get('surname'),
            surnamePrefix: $data->get('surname_prefix'),
            uziId: $data->get('uzi_id'),
            loaAuthn: $data->has('loa_authn') ? $data->get('loa_authn') : null,
            loaUzi: $data->get('loa_uzi'),
            uras: $relations
        );
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->initials . " " . $this->surnamePrefix . " " . $this->surname;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return $this->uziId;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifier(): string
    {
        return $this->uziId;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        throw new RuntimeException("Uzi uses can't have a password");
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken(): string
    {
        throw new RuntimeException("Do not remember cookie's");
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value): void
    {
        throw new RuntimeException("Do not remember cookie's");
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName(): string
    {
        throw new RuntimeException("Do not remember cookie's");
    }
}
