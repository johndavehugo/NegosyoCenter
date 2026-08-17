<?php

function uuidV7(string $prefix = ''): string
{
    $tsHex   = sprintf('%012x', (int) (microtime(true) * 1000));
    $randHex = bin2hex(random_bytes(9));

    $uuid = sprintf(
        '%s-%s-7%s-%s%s-%s',
        substr($tsHex, 0, 8),
        substr($tsHex, 8, 4),
        substr($randHex, 0, 3),
        dechex(0x8 | (hexdec(substr($randHex, 3, 1)) & 0x3)),
        substr($randHex, 4, 3),
        substr($randHex, 6, 12)
    );

    return $prefix . $uuid;
}