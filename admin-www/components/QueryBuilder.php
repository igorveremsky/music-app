<?php
/**
 * ActiveRecord for API
 *
 * @link      https://github.com/hiqdev/yii2-hiart
 * @package   yii2-hiart
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace app\components;

use hiqdev\hiart\Query;
use yii\helpers\Inflector;
use yii\helpers\Json;

class QueryBuilder extends \hiqdev\hiart\AbstractQueryBuilder {
	protected $authHeaders = [];

	/**
	 * This function is for you to provide your authentication.
	 *
	 * @param Query $query
	 */
	public function buildAuth(Query $query) {
		$auth = $this->db->getAuth();
		if (isset($auth['headerToken'])) {
			$this->authHeaders['Authorization'] = 'token ' . $auth['headerToken'];
		}
		if (isset($auth['headerBearer'])) {
			$this->authHeaders['Authorization'] = 'Bearer ' . $auth['headerBearer'];
		}
	}

	public function buildMethod(Query $query) {
		static $defaultMethods = [
			'get' => 'GET',
			'put' => 'PUT',
			'head' => 'HEAD',
			'post' => 'GET',
			'search' => 'GET',
			'insert' => 'POST',
			'update' => 'PUT',
			'delete' => 'DELETE',
		];

		return isset($defaultMethods[$query->action]) ? $defaultMethods[$query->action] : 'POST';
	}

	public function buildUri(Query $query) {
		$from = is_array($query->from) ? reset($query->from) : $query->from;
		$from = Inflector::pluralize($from);

		if ($query->action === 'update' || $query->action === 'delete') {
			$from .= '/' . $query->body['id'];
		}

		if (isset($query->where['id'])) {
			$from .= '/' . $query->where['id'];
		}

		return $from;
	}

	public function buildHeaders(Query $query) {
		return array_merge(['Content-Type' => 'application/json'], $this->authHeaders);
	}

	public function buildProtocolVersion(Query $query) {
		return null;
	}

	public function buildQueryParams(Query $query) {
		unset($query->where['id']);

		return $query->where;
	}

	public function buildFormParams(Query $query) {
		return [];
	}

	public function buildBody(Query $query) {
		unset($query->body['id']);

		return Json::encode($query->body);
	}
}
