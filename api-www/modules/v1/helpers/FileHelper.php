<?php

namespace app\modules\v1\helpers;

use Yii;

class FileHelper {
	/**
	 * @param $src
	 *
	 * @return bool
	 */
	public static function isExistFromSrc($src) {
		return (empty($src)) ? false : (self::isRemoteSrc($src) ? self::isExistRemoteSrc($src) : file_exists(self::getPathFromSrc($src)));
	}

	/**
	 * @param $src
	 *
	 * @return bool|string
	 */
	public static function getPathFromSrc($src) {
		return Yii::getAlias('@webroot'.((strpos($src, '/') === 0) ? '' : '/').$src);
	}

	/**
	 * @param $src
	 *
	 * @return bool
	 */
	public static function isRemoteSrc($src) {
		$parsedSrc = parse_url($src);

		return isset($parsedSrc['host']) && $parsedSrc['host'] !== gethostname();
	}

	/**
	 * @param $src
	 *
	 * @return bool
	 */
	public static function isExistRemoteSrc($src) {
		$ch = curl_init($src);

		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		return $retcode == 200;
	}
}