<?php

class ReferralCodeGenerator {
    private const CHARSET = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

    private string $prefix;

    public function __construct(string $prefix = '') {
        $this->prefix = strtoupper($prefix);
    }

    /**
     * Generate satu kode referral 8 karakter
     */
    public function generate(): string {
        $charset    = self::CHARSET;
        $charsetLen = strlen($charset);
        $code       = '';

        for ($i = 0; $i < 8; $i++) {
            $code .= $charset[random_int(0, $charsetLen - 1)];
        }

        return $this->prefix . $code;
    }

    /**
     * Generate banyak kode sekaligus (dijamin tidak duplikat)
     */
    public function generateBatch(int $count): array {
        $codes = [];

        while (count($codes) < $count) {
            $code         = $this->generate();
            $codes[$code] = $code;
        }

        return array_values($codes);
    }

    /**
     * Generate kode deterministik dari User ID
     */
    public function generateFromUserId(int $userId): string {
        $charset    = self::CHARSET;
        $charsetLen = strlen($charset);
        $hash       = hash('sha256', 'SALT_REF_2024_' . $userId);
        $code       = '';

        for ($i = 0; $i < 8; $i++) {
            $code .= $charset[hexdec($hash[$i * 2] . $hash[$i * 2 + 1]) % $charsetLen];
        }

        return $this->prefix . $code;
    }

    /**
     * Validasi kode referral
     */
    public function validate(string $code): bool {
        $code = strtoupper(trim($code));

        // Hapus prefix jika ada dan cocok
        if ($this->prefix !== '' && strpos($code, $this->prefix) === 0) {
            $code = substr($code, strlen($this->prefix));
        }

        // Pastikan tepat 8 karakter dari charset yang valid
        $pattern = '/^[' . preg_quote(self::CHARSET, '/') . ']{8}$/';

        return (bool) preg_match($pattern, $code);
    }
}

?>