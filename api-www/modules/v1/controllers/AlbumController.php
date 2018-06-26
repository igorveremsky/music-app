<?php

namespace app\modules\v1\controllers;

use app\modules\v1\models\Image;
use Yii;
use app\modules\v1\models\Album;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

/**
 * Class DefaultController
 * @package app\modules\v1\controllers
 */
class AlbumController extends DefaultController {
	public $modelClass = Album::class;
}