<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace DotBlue\Mandrill\Utils;



/**
 * @author David Grudl
 */
class MimeTypeDetector
{
	/**
	 * @param string $data
	 * @return string
	 */
	public static function fromString($data)
	{
		if (extension_loaded('fileinfo') && preg_match('#^(\S+/[^\s;]+)#', finfo_buffer(finfo_open(FILEINFO_MIME), $data), $m)) {
			return $m[1];

		} elseif (strncmp($data, "\xff\xd8", 2) === 0) {
			return 'image/jpeg';

		} elseif (strncmp($data, "\x89PNG", 4) === 0) {
			return 'image/png';

		} elseif (strncmp($data, "GIF", 3) === 0) {
			return 'image/gif';

		} else {
			return 'application/octet-stream';
		}
	}
}
