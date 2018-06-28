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

use Yii;
use yii\grid\Column;
use yii\helpers\Html;
use yii\helpers\Url;

class FavoriteColumn extends Column {
	/**
	 * {@inheritdoc}
	 */
	public $header = 'Favorite';

	/**
	 * {@inheritdoc}
	 */
	protected function renderDataCellContent($model, $key, $index) {
		$action = ($model->is_favorite) ? 'unfavorite' : 'favorite';
		$label = ($model->is_favorite) ? 'Dislike' : 'Like';
		$iconName = ($model->is_favorite) ? 'star-empty' : 'star';

		$url = Url::toRoute([$action, 'id' => (string) $key]);

		return Html::a(Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]), $url, [
			'title' => $label,
			'aria-label' => $label,
			'data-pjax' => '0',
			'data-method' => 'post',
		]);
	}
}