<?php

/**
 * Establishes a connection to the Redis server
 *
 * @return Redis|null
 */
function getRedisConnection(): ?Redis {
    try {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        return $redis;
    } catch (Exception $e) {
        error_log('[AB Test] Redis connection failed: ' . $e->getMessage());
        return null;
    }
}

/**
 * Checks if Redis is available
 *
 * @return bool
 */
function isRedisAvailable(): bool {
    try {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379, 1); // 1 second timeout
        return $redis->ping() === true || $redis->ping() === '+PONG';
    } catch (Exception $e) {
        error_log('[AB Test] Redis ping failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Retrieves the assigned variant for a given visitor ID ,returns null if the visitor is not
 * found in Redis
 *
 * @param string $visitorId
 * @return string|null
 */
function getVariant(string $visitorId): ?string {
    $redis = getRedisConnection();

    if ($redis === null) {
        return null;
    }

    $key    = "ab_test:variant:{$visitorId}";
    $result = $redis->get($key);

    return $result !== false ? $result : null;
}

/**
 * Saves the assigned variant for a given visitor ID, variants are stored for 30 day
 *
 * @param string $visitorId
 * @param string $variant
 * @return bool
 */
function saveVariant(string $visitorId, string $variant): bool {
    $redis = getRedisConnection();

    if ($redis === null) {
        return false;
    }

    $key = "ab_test:variant:{$visitorId}";
    $ttl = 60 * 60 * 24 * 30; // 30 days in seconds

    return $redis->setex($key, $ttl, $variant);
}