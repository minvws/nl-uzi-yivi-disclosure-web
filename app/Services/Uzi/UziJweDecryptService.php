<?php

declare(strict_types=1);

namespace App\Services\Uzi;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A128CBCHS256;
use Jose\Component\Encryption\Algorithm\KeyEncryption\RSAOAEP;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\Compression\Deflate;
use Jose\Component\Encryption\JWEDecrypter;
use Jose\Component\Encryption\Serializer\CompactSerializer;
use Jose\Component\Encryption\Serializer\JWESerializerManager;
use Jose\Component\KeyManagement\JWKFactory;
use RuntimeException;

class UziJweDecryptService implements UziJweDecryptInterface
{
    public function __construct(
        protected string $decryptionKeyPath,
    ) {
    }

    /**
     * Decrypts the given JWE and returns JWT.
     *
     * @throws RuntimeException
     */
    public function decrypt(string $jwe): string
    {
        // Decrypt JWE
        $decryptionKey = JWKFactory::createFromKeyFile("../" . $this->decryptionKeyPath);
        $keyEncryptionAlgorithmManager = new AlgorithmManager([new RSAOAEP()]);
        $contentEncryptionAlgorithmManager = new AlgorithmManager([new A128CBCHS256()]);
        $compressionMethodManager = new CompressionMethodManager([new Deflate()]);
        $jweDecrypter = new JWEDecrypter(
            $keyEncryptionAlgorithmManager,
            $contentEncryptionAlgorithmManager,
            $compressionMethodManager
        );

        $serializerManager = new JWESerializerManager([new CompactSerializer()]);

        $jwt = $serializerManager->unserialize($jwe);

        // Success of decryption, $jwe is now decrypted
        $success = $jweDecrypter->decryptUsingKey($jwt, $decryptionKey, 0);
        if (!$success) {
            throw new RuntimeException('Failed to decrypt JWE');
        }

        return $jwt->getPayload();
    }
}
