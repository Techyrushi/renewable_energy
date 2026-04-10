<?php

declare(strict_types=1);

namespace PHPMailer\PHPMailer;

class PHPMailer
{
	public string $CharSet = 'UTF-8';
	public string $Host = '';
	public int $Port = 587;
	public string $Username = '';
	public string $Password = '';
	public bool $SMTPAuth = false;
	public string $SMTPSecure = '';

	public string $Subject = '';
	public string $Body = '';
	public string $AltBody = '';

	private bool $useSMTP = false;
	private bool $isHTML = false;
	private string $fromEmail = '';
	private string $fromName = '';
	private array $to = [];
	private array $replyTo = [];

	public function __construct(bool $exceptions = true)
	{
	}

	public function isSMTP(): void
	{
		$this->useSMTP = true;
	}

	public function isMail(): void
	{
		$this->useSMTP = false;
	}

	public function setFrom(string $address, string $name = ''): void
	{
		$this->fromEmail = trim($address);
		$this->fromName = $name;
	}

	public function addAddress(string $address, string $name = ''): void
	{
		$this->to[] = ['email' => trim($address), 'name' => $name];
	}

	public function addReplyTo(string $address, string $name = ''): void
	{
		$this->replyTo = ['email' => trim($address), 'name' => $name];
	}

	public function msgHTML(string $html): void
	{
		$this->isHTML = true;
		$this->Body = $html;
	}

	public function send(): bool
	{
		if (!$this->hasValidFrom() || !$this->hasValidTo()) {
			throw new Exception('Invalid From/To');
		}
		if ($this->useSMTP && trim($this->Host) !== '') {
			return $this->smtpSend();
		}
		return $this->mailSend();
	}

	private function hasValidFrom(): bool
	{
		return $this->fromEmail !== '' && filter_var($this->fromEmail, FILTER_VALIDATE_EMAIL);
	}

	private function hasValidTo(): bool
	{
		if (!$this->to) {
			return false;
		}
		foreach ($this->to as $t) {
			if (!isset($t['email']) || !filter_var($t['email'], FILTER_VALIDATE_EMAIL)) {
				return false;
			}
		}
		return true;
	}

	private function mailSend(): bool
	{
		$to = [];
		foreach ($this->to as $t) {
			$to[] = $this->formatAddress($t['email'], (string)($t['name'] ?? ''));
		}

		$headers = [];
		$headers[] = 'MIME-Version: 1.0';
		if ($this->isHTML) {
			$headers[] = 'Content-type: text/html; charset=' . $this->CharSet;
		} else {
			$headers[] = 'Content-type: text/plain; charset=' . $this->CharSet;
		}
		$headers[] = 'From: ' . $this->formatAddress($this->fromEmail, $this->fromName);
		if ($this->replyTo) {
			$headers[] = 'Reply-To: ' . $this->formatAddress($this->replyTo['email'], (string)($this->replyTo['name'] ?? ''));
		}

		$body = $this->isHTML ? $this->Body : ($this->AltBody !== '' ? $this->AltBody : $this->Body);
		if (!$this->isHTML) {
			$body = $body !== '' ? $body : $this->AltBody;
		}

		return @mail(implode(', ', $to), $this->Subject, $body, implode("\r\n", $headers));
	}

	private function smtpSend(): bool
	{
		$secure = SMTP::normalizeSecure($this->SMTPSecure);
		$host = trim($this->Host);
		$port = $this->Port > 0 ? $this->Port : 587;
		$remote = ($secure === 'ssl' ? 'ssl://' : '') . $host;
		$fp = @fsockopen($remote, $port, $errno, $errstr, 10);
		if (!$fp) {
			throw new Exception('SMTP connect failed: ' . $errstr);
		}
		stream_set_timeout($fp, 10);

		$this->smtpExpect($fp, [220]);
		$hostname = (string)($_SERVER['SERVER_NAME'] ?? 'localhost');
		$this->smtpWrite($fp, 'EHLO ' . $hostname);
		$ehlo = $this->smtpReadMultiline($fp);
		if (!$this->smtpResponseOk($ehlo, 250)) {
			$this->smtpWrite($fp, 'HELO ' . $hostname);
			$this->smtpExpect($fp, [250]);
		}

		if ($secure === 'tls') {
			$this->smtpWrite($fp, 'STARTTLS');
			$this->smtpExpect($fp, [220]);
			$cryptoOk = @stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
			if (!$cryptoOk) {
				fclose($fp);
				throw new Exception('STARTTLS failed');
			}
			$this->smtpWrite($fp, 'EHLO ' . $hostname);
			$this->smtpExpect($fp, [250]);
		}

		if ($this->SMTPAuth && $this->Username !== '') {
			$this->smtpWrite($fp, 'AUTH LOGIN');
			$this->smtpExpect($fp, [334]);
			$this->smtpWrite($fp, base64_encode($this->Username));
			$this->smtpExpect($fp, [334]);
			$this->smtpWrite($fp, base64_encode($this->Password));
			$this->smtpExpect($fp, [235, 503]);
		}

		$this->smtpWrite($fp, 'MAIL FROM:<' . $this->fromEmail . '>');
		$this->smtpExpect($fp, [250]);

		foreach ($this->to as $t) {
			$this->smtpWrite($fp, 'RCPT TO:<' . $t['email'] . '>');
			$this->smtpExpect($fp, [250, 251]);
		}

		$this->smtpWrite($fp, 'DATA');
		$this->smtpExpect($fp, [354]);

		$mime = $this->buildMimeMessage();
		if ($mime !== '' && $mime[0] === '.') {
			$mime = '.' . $mime;
		}
		$mime = str_replace("\r\n.", "\r\n..", $mime);
		$this->smtpWriteRaw($fp, $mime . "\r\n.\r\n");
		$this->smtpExpect($fp, [250]);

		$this->smtpWrite($fp, 'QUIT');
		fclose($fp);
		return true;
	}

	private function buildMimeMessage(): string
	{
		$to = [];
		foreach ($this->to as $t) {
			$to[] = $this->formatAddress($t['email'], (string)($t['name'] ?? ''));
		}

		$headers = [];
		$headers[] = 'Date: ' . date('r');
		$headers[] = 'To: ' . implode(', ', $to);
		$headers[] = 'From: ' . $this->formatAddress($this->fromEmail, $this->fromName);
		$headers[] = 'Subject: ' . $this->encodeHeader($this->Subject);
		$headers[] = 'MIME-Version: 1.0';
		if ($this->replyTo) {
			$headers[] = 'Reply-To: ' . $this->formatAddress($this->replyTo['email'], (string)($this->replyTo['name'] ?? ''));
		}

		$bodyText = $this->AltBody !== '' ? $this->AltBody : strip_tags($this->Body);
		if (!$this->isHTML) {
			$bodyText = $this->Body !== '' ? $this->Body : $bodyText;
		}

		if ($this->isHTML) {
			$boundary = 'b' . bin2hex(random_bytes(8));
			$headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
			$out = implode("\r\n", $headers) . "\r\n\r\n";
			$out .= '--' . $boundary . "\r\n";
			$out .= 'Content-Type: text/plain; charset=' . $this->CharSet . "\r\n";
			$out .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
			$out .= $bodyText . "\r\n\r\n";
			$out .= '--' . $boundary . "\r\n";
			$out .= 'Content-Type: text/html; charset=' . $this->CharSet . "\r\n";
			$out .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
			$out .= $this->Body . "\r\n\r\n";
			$out .= '--' . $boundary . "--\r\n";
			return $out;
		}

		$headers[] = 'Content-Type: text/plain; charset=' . $this->CharSet;
		$headers[] = 'Content-Transfer-Encoding: 8bit';
		return implode("\r\n", $headers) . "\r\n\r\n" . $bodyText;
	}

	private function encodeHeader(string $s): string
	{
		$s = trim($s);
		if ($s === '') {
			return '';
		}
		return '=?' . $this->CharSet . '?B?' . base64_encode($s) . '?=';
	}

	private function formatAddress(string $email, string $name): string
	{
		$email = trim($email);
		$name = trim($name);
		if ($name === '') {
			return $email;
		}
		return $this->encodeHeader($name) . ' <' . $email . '>';
	}

	private function smtpWrite($fp, string $line): void
	{
		$this->smtpWriteRaw($fp, $line . "\r\n");
	}

	private function smtpWriteRaw($fp, string $data): void
	{
		$len = strlen($data);
		$wrote = 0;
		while ($wrote < $len) {
			$n = @fwrite($fp, substr($data, $wrote));
			if ($n === false || $n === 0) {
				throw new Exception('SMTP write failed');
			}
			$wrote += $n;
		}
	}

	private function smtpReadLine($fp): string
	{
		$line = (string)@fgets($fp, 8192);
		if ($line === '') {
			$meta = stream_get_meta_data($fp);
			if (isset($meta['timed_out']) && $meta['timed_out']) {
				throw new Exception('SMTP timeout');
			}
		}
		return $line;
	}

	private function smtpReadMultiline($fp): array
	{
		$lines = [];
		while (true) {
			$line = $this->smtpReadLine($fp);
			if ($line === '') {
				break;
			}
			$lines[] = $line;
			if (strlen($line) < 4) {
				break;
			}
			if ($line[3] !== '-') {
				break;
			}
		}
		return $lines;
	}

	private function smtpResponseOk(array $lines, int $code): bool
	{
		if (!$lines) {
			return false;
		}
		$last = trim((string)end($lines));
		return str_starts_with($last, (string)$code);
	}

	private function smtpExpect($fp, array $codes): void
	{
		$lines = $this->smtpReadMultiline($fp);
		if (!$lines) {
			throw new Exception('SMTP empty response');
		}
		$last = trim((string)end($lines));
		$got = (int)substr($last, 0, 3);
		foreach ($codes as $c) {
			if ($got === (int)$c) {
				return;
			}
		}
		throw new Exception('SMTP unexpected response: ' . $last);
	}
}
