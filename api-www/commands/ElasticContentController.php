<?php
/**
 * Created by PhpStorm.
 * User: Igor-Web-Development
 * Date: 08.02.2018
 * Time: 19:13
 */

namespace app\commands;

use app\modules\v1\models\elastic\GenreElastic;
use app\modules\v1\models\Genre;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class ElasticContentController extends Controller {

	/* ******************ACTIONS************** */

	public function actionIndexInit() {
		GenreElastic::createIndex();
		$this->stdout('Complete...'.PHP_EOL, Console::FG_GREEN);
	}

	public function actionIndexDelete() {
		GenreElastic::deleteIndex();
		$this->stdout('Complete...'.PHP_EOL, Console::FG_GREEN);
	}

	public function actionMapUpdate() {
		GenreElastic::updateMapping();
		$this->stdout('Complete...'.PHP_EOL, Console::FG_GREEN);
	}

	public function actionImportFromDb() {
		$elasticModel = new GenreElastic();
		$models = Genre::find()->select($elasticModel->attributes())->asArray()->all();
		unset($elasticModel);

		foreach ($models as $model) {
			$this->stdout('Import model '.json_encode($model).'...'.PHP_EOL, Console::FG_YELLOW);
			if (GenreElastic::find()->where($model)->exists()) {
				$this->stdout('Exist...'.PHP_EOL, Console::FG_YELLOW);

				continue;
			}

			$elasticModel = new GenreElastic($model);
			if ($elasticModel->save()) {
				$this->stdout('Complete...'.PHP_EOL, Console::FG_GREEN);
			} else {
				$this->stdout('Error...'.PHP_EOL, Console::FG_RED);
			};
		}
	}
}