<?php

/**
 * Simulates AB Tasty Flagship SDK in Bucketing mode.
 */

const TRAFFIC_SPLIT = 50; // 50% control, 50% variation_b

/**
 * Decides which variant a visitor should see
 *
 * @param string $visitorId
 * @return string "control" or "variation_b"
 */
function decideVariant(string $visitorId): string {
    // Convert the visitor ID hash into a number between 0 and 99
    $bucket = abs(crc32($visitorId)) % 100;

    return $bucket < TRAFFIC_SPLIT ? 'control' : 'variation_b';
}