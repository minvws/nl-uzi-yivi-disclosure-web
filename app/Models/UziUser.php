<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Jose\Easy\ParameterBag;
use Illuminate\Contracts\Auth\Authenticatable;
use Nette\NotImplementedException;

class UziUser implements Authenticatable
{
    public function __construct(
        public string $initials,
        public string $surname,
        public string $surnamePrefix,
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
        $required_keys = array("relations", "initials", "surname", "surname_prefix", "uzi_id", "loa_uzi");
        $missing_keys = array();
        foreach ($required_keys as &$key) {
            if (!$data->has($key)) {
                $missing_keys[] = $key;
            }
        }
        if (count($missing_keys) > 0) {
            return null;
        }
        if (
                !$data->has('relations')
                or !$data->has('i')
        ) {
            $relations = [];
        }
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

    public static function deserializeFromJson(string $value): ?UziUser
    {
        $uras = [];
        try {
            $decoded = json_decode($value);
            foreach ($decoded->uras as $ura) {
                $uras[] = new UziRelation($ura->entityName, $ura->ura, $ura->roles);
            }
            return new UziUser(
                $decoded->initials,
                $decoded->surname,
                $decoded->surnamePrefix,
                $decoded->uziId,
                $decoded->loaAuthn,
                $decoded->loaUzi,
                $uras
            );
        } catch (\Exception $e) {
            report($e);
            Log::info("Trying to reconstruct a uzi user from session failed");
            return null;
        }
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
        throw new NotImplementedException("Uzi uses can't have a password");
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken(): string
    {
        throw new NotImplementedException("Do not remember cookie's");
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value): string
    {
        throw new NotImplementedException("Do not remember cookie's");
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName(): string
    {
        throw new NotImplementedException("Do not remember cookie's");
    }
}
