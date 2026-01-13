<?php

namespace App\Config;

class Env
{
    public static function load(\)
    {
        if (!file_exists(\)) {
            return;
        }

        \ = file(\, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach (\ as \) {
            if (strpos(trim(\), '#') === 0) {
                continue;
            }

            list(\, \) = explode('=', \, 2);
            \ = trim(\);
            \ = trim(\);

            if (!array_key_exists(\, \) && !array_key_exists(\, \)) {
                putenv(sprintf('%s=%s', \, \));
                \[\] = \;
                \[\] = \;
            }
        }
    }
}
