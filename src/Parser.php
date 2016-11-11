<?php
/**
 * Copyright (C) Youthweb e.V. 2004 - 2016
 * All rights reserved.
 */

namespace Youthweb\SmileyEmojiMigration;

/**
 * README.md parser
 */

class Parser
{
	/**
	 * parse the README.md
	 */
	public function parseReadme()
	{
		$file_path = realpath(__DIR__) . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'README.md';

		$content = file_get_contents($file_path);

		$content = explode('---------', $content);

		$content = array_pop($content);

		$entries = [];

		foreach (explode("\n", $content) as $key => $line)
		{
			if ( trim($line) === '' )
			{
				continue;
			}

			$parts = explode('|', $line);

			$entries[] = [
				'smiley_code' => trim($parts[0], ' `'),
				'smiley_filename' => trim($parts[4], ' `'),
				'smiley_url' => trim($parts[1], ' ![]()'),
				'emoji_urls' => $this->parseEmojiUrls($parts[2]),
				'emoji_codes' => $this->parseEmojiCodes($parts[3]),
			];
		}

		//$output = json_encode($entries);

		return $entries;
	}

	/**
	 * parse the emoji urls
	 */
	public function parseEmojiUrls($string)
	{
		$urls = [];

		$string = trim($string);

		if ($string === ':question:' or $string === '')
		{
			return $urls;
		}

		$parts = explode(')![](', $string);

		foreach ($parts as $part)
		{
			$urls[] = trim($part, ' ![]()');
		}

		return $urls;
	}

	/**
	 * parse the emoji codes
	 */
	public function parseEmojiCodes($string)
	{
		$codes = [];

		$string = trim($string);
		$string = trim($string, ' `');

		if ($string === '')
		{
			return $codes;
		}

		$parts = explode('::', $string);

		foreach ($parts as $part)
		{
			$codes[] = sprintf(':%s:', trim($part, ' :'));
		}

		return $codes;
	}
}