<?php

namespace app\modules\v1\controllers;

use app\modules\v1\models\Track;
use Yii;

/**
 * Class TrackController
 * @package app\modules\v1\controllers
 */
class TrackController extends DefaultController {
	public $modelClass = Track::class;
}