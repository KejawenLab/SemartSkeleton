<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Util;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
final class Encryptor
{
    const CIPHER_ALGORITHM = 'aes-256-cbc';
    const HASH_ALGORITHM = 'sha256';

    public static function encrypt(string $plainText, string $secretKey)
    {
        return openssl_encrypt($plainText, self::CIPHER_ALGORITHM, $secretKey, 0, substr(hash(self::HASH_ALGORITHM, $secretKey), 0, 16));
    }

    public static function decrypt(string $encryptedText, string $secretKey)
    {
        return openssl_decrypt($encryptedText, self::CIPHER_ALGORITHM, $secretKey, 0, substr(hash(self::HASH_ALGORITHM, $secretKey), 0, 16));
    }
}
