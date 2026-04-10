<?php

declare(strict_types=1);

namespace PHPMailer\PHPMailer;

class SMTP
{
	public static function normalizeSecure(string $secure): string
	{
		$s = strtolower(trim($secure));
		if ($s === 'ssl' || $s === 'tls') {
			return $s;
		}
		return '';
	}
}

