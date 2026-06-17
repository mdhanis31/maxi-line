<?php
include_once 'include/ReferralCodeGenerator.php';

// ── 1. Kode acak tanpa prefix ───────────────────────────────
$gen  = new ReferralCodeGenerator();
echo $gen->generate() . '<br>';
// → A3KXMB7N

// ── 2. Dengan prefix ────────────────────────────────────────
// ❌ PHP 8.0+ (named argument)  → new ReferralCodeGenerator(prefix: 'REF')
// ✅ PHP 7.4                    → new ReferralCodeGenerator('REF')
$gen  = new ReferralCodeGenerator('REF');
echo $gen->generate(). '<br>';
// → REFA3KXMB7N

// ── 3. Generate banyak sekaligus ────────────────────────────
$gen   = new ReferralCodeGenerator();
$codes = $gen->generateBatch(5);
print_r($codes);
echo '<br>';
// Array ( [0] => A3KXMB7N [1] => 9WNPVT4H ... )

// ── 4. Deterministik dari User ID ───────────────────────────
$gen  = new ReferralCodeGenerator();
echo $gen->generateFromUserId(2116) . '<br>';
// → Selalu output yang sama untuk ID 42

// ── 5. Validasi ─────────────────────────────────────────────
$gen = new ReferralCodeGenerator('REF');
echo $gen->validate('RUFN76SY5AK') ? 'Valid' : 'Tidak valid';
echo '<br>';
// → Valid
?>