<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class YooKassaWebhookIpAllowlist
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowlist = config('yookassa.webhook_ip_allowlist', []);
        if (empty($allowlist)) {
            return $next($request);
        }

        $ip = $request->ip();
        if (!$ip) {
            return response('Forbidden', 403);
        }

        foreach ($allowlist as $rule) {
            if ($this->ipMatchesRule($ip, (string) $rule)) {
                return $next($request);
            }
        }

        return response('Forbidden', 403);
    }

    private function ipMatchesRule(string $ip, string $rule): bool
    {
        $rule = trim($rule);
        if ($rule === '') {
            return false;
        }

        // Single IP
        if (!str_contains($rule, '/')) {
            return $ip === $rule;
        }

        [$subnet, $maskBits] = explode('/', $rule, 2);
        $maskBits = (int) $maskBits;

        $ipBin = @inet_pton($ip);
        $subnetBin = @inet_pton($subnet);
        if ($ipBin === false || $subnetBin === false) {
            return false;
        }
        if (strlen($ipBin) !== strlen($subnetBin)) {
            return false; // IPv4 vs IPv6 mismatch
        }

        $bytes = strlen($ipBin);
        $maxBits = $bytes * 8;
        if ($maskBits < 0 || $maskBits > $maxBits) {
            return false;
        }

        $fullBytes = intdiv($maskBits, 8);
        $remainderBits = $maskBits % 8;

        if ($fullBytes > 0) {
            if (substr($ipBin, 0, $fullBytes) !== substr($subnetBin, 0, $fullBytes)) {
                return false;
            }
        }

        if ($remainderBits === 0) {
            return true;
        }

        $mask = (0xFF << (8 - $remainderBits)) & 0xFF;
        $ipByte = ord($ipBin[$fullBytes]);
        $subnetByte = ord($subnetBin[$fullBytes]);

        return (($ipByte & $mask) === ($subnetByte & $mask));
    }
}

