<?php

declare(strict_types=1);

namespace App\Services\Uzi;

interface UziJweDecryptInterface
{
    /**
     * @param string $jwe The JWE to decrypt.
     * @return string The decrypted JWE
     */
    public function decrypt(string $jwe): string;
}
