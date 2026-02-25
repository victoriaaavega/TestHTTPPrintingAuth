<?php

require_once get_template_directory() . '/includes/fingerprint.php';
require_once get_template_directory() . '/includes/redis-client.php';
require_once get_template_directory() . '/includes/flagship-simulator.php';

/**
 * Orchestrates the complete AB test flow:
 * 1. Generates the visitor ID from the request fingerprint
 * 2. Checks Redis for an existing variant assignment
 * 3. If new visitor: asks Flagship (simulated) for a variant and saves it to Redis
 * 4. If returning visitor: retrieves their variant from Redis
 * 5. Sets cache bypass headers so the page is never served from cache
 * 6. Sets a cookie for Heap identity sync (additional)
 *
 * @return array{visitorId: string, variant: string}
 */
function runExperiment(): array {
    $visitorId = generateVisitorId();

    setCacheBypassHeaders();

    setHeapIdentityCookie($visitorId); //sets cookie

    if (!isRedisAvailable()) {
        error_log("[AB Test] Redis unavailable, serving control for visitor: {$visitorId}");
        return ['visitorId' => $visitorId, 'variant' => 'control'];
    }

    $variant = getVariant($visitorId);

    if ($variant !== null) {
        error_log("[AB Test] Returning visitor: {$visitorId} → {$variant}");
        return ['visitorId' => $visitorId, 'variant' => $variant];
    }

    $variant = decideVariant($visitorId);

    $saved = saveVariant($visitorId, $variant);

    if (!$saved) {
        error_log("[AB Test] Warning: could not save variant to Redis for visitor: {$visitorId}");
    }

    error_log("[AB Test] New visitor: {$visitorId} → {$variant}");

    return ['visitorId' => $visitorId, 'variant' => $variant];
}

/**
 * Sets headers to prevent the page from being served from cache
 */
function setCacheBypassHeaders(): void {
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
}

/**
 * Sets a first-party cookie with the visitor ID for Heap identity sync
 *
 * @param string $visitorId
 */
function setHeapIdentityCookie(string $visitorId): void {
    if (!isset($_COOKIE['heap_visitor_id'])) {
        setcookie(
            'heap_visitor_id',
            $visitorId,
            [
                'expires'  => time() + (60 * 60 * 24 * 30), // 30 days
                'path'     => '/',
                'secure'   => false,
                'httponly' => false,
                'samesite' => 'Lax',
            ]
        );
    }
}