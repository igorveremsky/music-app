<?php
/**
 * Created by PhpStorm.
 * User: Igor-Web-Development
 * Date: 08.02.2018
 * Time: 19:13
 */

namespace app\commands;

use app\models\TrackArtist;
use app\modules\v1\models\Album;
use app\modules\v1\models\Artist;
use app\modules\v1\models\Audiofile;
use app\modules\v1\models\Genre;
use app\modules\v1\models\Image;
use app\modules\v1\models\Track;
use Yii;
use yii\console\Controller;
use yii\console\widgets\Table;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class DemoContentController extends Controller {
	protected $deletedModels = [];

	const RELATIVE_MODEL_PREFIX = 'relative';

	// Table view constants
	const TABLE_MAX_ROW_COUNT = 6;
	const TABLE_MAX_COLUMN_CHAR_COUNT = 30;

	// Model attribute view constants
	const ATTRIBUTE_MAX_CHAR_COUNT = 100;

	public static function getDemoContentOptions($type = 'all') {
		$demoContentManualOptions = [
			'images' => [
				'modelClassName' => Image::class,
				'values' => [
					[
						'file_src' => 'https://i.scdn.co/image/3e01c54cd7173305ec77586f4191a7657eacd870', // america
					],
					[
						'file_src' => 'https://i.scdn.co/image/bdf096a7ffc6c2fa051adecf704b5695d316471a', // hopeless
					],
					[
						'file_src' => 'https://static.stereogum.com/uploads/2017/05/halseyaltpressphoto-1496252313-640x640.jpeg', // halsey
					],
					[
						'file_src' => 'https://24tv.ua/resources/photos/news/610x344_DIR/201710/873444.jpg?201711140124', // 30 seconds
					],
					[
						'file_src' => 'https://i.pinimg.com/736x/0c/fd/a3/0cfda3ba37b9c169b747d193476bd854--mel-eye-candy.jpg', // quavo
					],
				]
			],
			'audios' => [
				'modelClassName' => Audiofile::class,
				'values' => [
					[
						'file_src' => 'http://ol6.mp3party.net/online/8448/8448796.mp3', // proloque h
					],
					[
						'file_src' => 'http://ol6.mp3party.net/online/8447/8447958.mp3', // alone
					],
					[
						'file_src' => 'http://ol6.mp3party.net/online/8480/8480556.mp3', // bad at love
					],
					[
						'file_src' => 'http://ol1.mp3party.net/online/170/170647.mp3', // colors
					],
					[
						'file_src' => 'http://ol1.mp3party.net/online/170/170486.mp3', // Ghost
					],
					[
						'file_src' => 'http://ol6.mp3party.net/online/8448/8448530.mp3', // Lie
					],
					[
						'file_src' => 'http://ol6.mp3party.net/online/8447/8447960.mp3', // heaven in hiding
					],
					[
						'file_src' => 'http://ol4.mp3party.net/online/6894/6894843.mp3', // We are a new americana
					],
					[
						'file_src' => 'http://ol7.mp3party.net/online/8489/8489967.mp3', // walk on water
					],
					[
						'file_src' => 'http://ol8.mp3party.net/online/8540/8540256.mp3', // dangerous night
					],
					[
						'file_src' => 'http://ol9.mp3party.net/online/8570/8570866.mp3', // love is madness
					],
				]
			],
			'genres' => [
				'modelClassName' => Genre::class,
				'values' => [
					[
						'id' => 1,
						'name' => 'rock',
					],
					[
						'id' => 2,
						'name' => 'pop',
					],
				]
			],
			'albums' => [
				'modelClassName' => Album::class,
				'values' => [
					[
						'name' => 'AMERICA',
						'year' => 2018,
						'genre_id' => [
							'relativeModelClassName' => Genre::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'rock'],
						],
						'cover_img_id' => [
							'relativeModelClassName' => Image::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'https://i.scdn.co/image/3e01c54cd7173305ec77586f4191a7657eacd870'],
						],
						'records_name' => '℗ ℗ 2018 Thirty Seconds to Mars, under exclusive license to Interscope Records',

					],
					[
						'name' => 'hopeless fountain kingdom',
						'year' => 2017,
						'genre_id' => [
							'relativeModelClassName' => Genre::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'pop'],
						],
						'cover_img_id' => [
							'relativeModelClassName' => Image::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'https://i.scdn.co/image/bdf096a7ffc6c2fa051adecf704b5695d316471a'],
						],
						'records_name' => '℗ ℗ 2017 Astralwerks',
					]
				],
			],
			'artists' => [
				'modelClassName' => Artist::class,
				'values' => [
					[
						'name' => 'Thirty Seconds To Mars',
						'type' => Artist::TYPE_GROUP,
						'avatar_img_id' => [
							'relativeModelClassName' => Image::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'https://24tv.ua/resources/photos/news/610x344_DIR/201710/873444.jpg?201711140124'],
						],
					],
					[
						'name' => 'Halsey',
						'type' => Artist::TYPE_SINGLE,
						'avatar_img_id' => [
							'relativeModelClassName' => Image::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'https://static.stereogum.com/uploads/2017/05/halseyaltpressphoto-1496252313-640x640.jpeg'],
						],
					],
					[
						'name' => 'Quavo',
						'type' => Artist::TYPE_SINGLE,
						'avatar_img_id' => [
							'relativeModelClassName' => Image::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'https://i.pinimg.com/736x/0c/fd/a3/0cfda3ba37b9c169b747d193476bd854--mel-eye-candy.jpg'],
						],
					]
				],
			],
			'tracks' => [
				'modelClassName' => Track::class,
				'values' => [
					[
						'name' => 'The Prologue',
						'file_id' => [
							'relativeModelClassName' => Audiofile::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'http://ol6.mp3party.net/online/8448/8448796.mp3'],
						],
						'album_id' => [
							'relativeModelClassName' => Album::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'hopeless fountain kingdom'],
						],
						'album_number' => 1,
						'is_explicit' => 0,
					],
					[
						'name' => 'Alone',
						'file_id' => [
							'relativeModelClassName' => Audiofile::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'http://ol6.mp3party.net/online/8447/8447958.mp3'],
						],
						'album_id' => [
							'relativeModelClassName' => Album::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'hopeless fountain kingdom'],
						],
						'album_number' => 2,
						'is_explicit' => 0,
					],
					[
						'name' => 'Bad At Love',
						'file_id' => [
							'relativeModelClassName' => Audiofile::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'http://ol6.mp3party.net/online/8480/8480556.mp3'],
						],
						'album_id' => [
							'relativeModelClassName' => Album::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'hopeless fountain kingdom'],
						],
						'album_number' => 3,
						'is_explicit' => 0,
					],
					[
						'name' => 'Colors',
						'file_id' => [
							'relativeModelClassName' => Audiofile::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'http://ol1.mp3party.net/online/170/170647.mp3'],
						],
						'album_id' => [
							'relativeModelClassName' => Album::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'hopeless fountain kingdom'],
						],
						'album_number' => 4,
						'is_explicit' => 0,
					],
					[
						'name' => 'Ghost',
						'file_id' => [
							'relativeModelClassName' => Audiofile::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'http://ol1.mp3party.net/online/170/170486.mp3'],
						],
						'album_id' => [
							'relativeModelClassName' => Album::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'hopeless fountain kingdom'],
						],
						'album_number' => 5,
						'is_explicit' => 0,
					],
					[
						'name' => 'Lie',
						'file_id' => [
							'relativeModelClassName' => Audiofile::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'http://ol6.mp3party.net/online/8448/8448530.mp3'],
						],
						'album_id' => [
							'relativeModelClassName' => Album::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'hopeless fountain kingdom'],
						],
						'album_number' => 6,
						'is_explicit' => 0,
					],
					[
						'name' => 'Heaven In Hiding',
						'file_id' => [
							'relativeModelClassName' => Audiofile::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'http://ol6.mp3party.net/online/8447/8447960.mp3'],
						],
						'album_id' => [
							'relativeModelClassName' => Album::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'hopeless fountain kingdom'],
						],
						'album_number' => 7,
						'is_explicit' => 1,
					],
					[
						'name' => 'New Americana',
						'file_id' => [
							'relativeModelClassName' => Audiofile::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'http://ol4.mp3party.net/online/6894/6894843.mp3'],
						],
						'album_id' => [
							'relativeModelClassName' => Album::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'hopeless fountain kingdom'],
						],
						'album_number' => 8,
						'is_explicit' => 0,
					],
					[
						'name' => 'Walk On Water',
						'file_id' => [
							'relativeModelClassName' => Audiofile::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'http://ol7.mp3party.net/online/8489/8489967.mp3'],
						],
						'album_id' => [
							'relativeModelClassName' => Album::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'AMERICA'],
						],
						'album_number' => 1,
						'is_explicit' => 0,
					],
					[
						'name' => 'Dangerous night',
						'file_id' => [
							'relativeModelClassName' => Audiofile::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'http://ol8.mp3party.net/online/8540/8540256.mp3'],
						],
						'album_id' => [
							'relativeModelClassName' => Album::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'AMERICA'],
						],
						'album_number' => 2,
						'is_explicit' => 0,
					],
					[
						'name' => 'Love Is Madness',
						'file_id' => [
							'relativeModelClassName' => Audiofile::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['file_src' => 'http://ol9.mp3party.net/online/8570/8570866.mp3'],
						],
						'album_id' => [
							'relativeModelClassName' => Album::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'AMERICA'],
						],
						'album_number' => 3,
						'is_explicit' => 0,
					],
				],
			],
			'tracks_artists' => [
				'modelClassName' => TrackArtist::class,
				'values' => [
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'The Prologue'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Halsey'],
						],
					],
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Alone'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Halsey'],
						],
					],
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Bad At Love'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Halsey'],
						],
					],
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Colors'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Halsey'],
						],
					],
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Ghost'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Halsey'],
						],
					],
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Lie'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Halsey'],
						],
					],
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Heaven In Hiding'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Halsey'],
						],
					],
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'New Americana'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Halsey'],
						],
					],
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Walk On Water'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Thirty Seconds To Mars'],
						],
					],
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Dangerous night'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Thirty Seconds To Mars'],
						],
					],
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Love Is Madness'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Thirty Seconds To Mars'],
						],
					],
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Love Is Madness'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Halsey'],
						],
					],
					[
						'track_id' => [
							'relativeModelClassName' => Track::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Lie'],
						],
						'artist_id' => [
							'relativeModelClassName' => Artist::class,
							'dbForeignAttributeTo' => 'id',
							'relativeModelAttribute' => ['name' => 'Quavo'],
						],
					],
				],
			],
		];

		return $demoContentManualOptions;
	}

	/* ******************ACTIONS************** */

	public function actionReImport($contentType = 'all') {
		$demoContentOptions = $this->getDemoContentOptions($contentType);
		if (isset($contentType) && ArrayHelper::keyExists($contentType, $demoContentOptions)) {
			return $this->reImportContentItem($demoContentOptions[$contentType]);
		} elseif ($contentType === 'all') {
			foreach ($demoContentOptions as $demoContentOptionsItem) {
				$this->reImportContentItem($demoContentOptionsItem);
			}

			return 1;
		} else {
			$this->stdout('Not valid key...' . PHP_EOL, Console::FG_RED);
		}
	}

	public function actionDelete($contentType = 'all') {
		$demoContentOptions = $this->demoContentOptions;
		if (isset($contentType) && ArrayHelper::keyExists($contentType, $demoContentOptions)) {
			return $this->deleteContentItem($demoContentOptions[$contentType]);
		} elseif ($contentType === 'all') {
			foreach ($demoContentOptions as $demoContentOptionsItem) {
				$this->deleteContentItem($demoContentOptionsItem);
			}

			return 1;
		} else {
			$this->stdout('Not valid key...' . PHP_EOL, Console::FG_RED);
		}
	}

	public function actionImport($contentType = 'all') {
		$demoContentOptions = $this->getDemoContentOptions($contentType);
		if ($contentType !== 'all' && ArrayHelper::keyExists($contentType, $demoContentOptions)) {
			return $this->importContentItem($demoContentOptions[$contentType]);
		} elseif ($contentType === 'all') {
			foreach ($demoContentOptions as $demoContentOptionsItem) {
				$this->importContentItem($demoContentOptionsItem);
			}

			return 1;
		} else {
			$this->stdout('Not valid key...' . PHP_EOL, Console::FG_RED);
		}
	}

	/* ************************SANITIZE DEMO CONTENT ITEMS************************************* */

	protected function sanitizeContentItemValues($contentItem) {
		$relativeModelOptions = ArrayHelper::getValue($contentItem, 'relativeModel');
		$relativeModelWithOptions = ArrayHelper::getValue($contentItem, 'relativeModelWith');
		$dbForeignOptions = ArrayHelper::getValue($contentItem, 'dbForeignKey');

		$contentItemValues = $contentItem['values'];

		foreach ($contentItemValues as $contentItemValueKey => $contentItemValue) {
			foreach ($contentItemValue as $contentItemValueValueKey => $contentItemValueValue) {
				$contentItemValue[$contentItemValueValueKey] = $this->sanitizeContentItemValue($contentItemValueValue);
				$contentItemValues[$contentItemValueKey] = $contentItemValue;
			}
		}

		if (empty($relativeModelOptions) && empty($dbForeignOptions)) {
			return $contentItemValues;
		} else {
			$relativeModelClassName = $relativeModelOptions[0];
			$relativeModelAttributeName = $relativeModelOptions[1];
			$relativeValueKeyName = static::RELATIVE_MODEL_PREFIX.'_'.$relativeModelAttributeName;

			$dbForeignAttributeFrom = $dbForeignOptions[0];
			$dbForeignAttributeTo = $dbForeignOptions[1];

			if (!empty($relativeModelWithOptions) && count($relativeModelWithOptions) == 2) {
				$relativeModelWithId = $relativeModelWithOptions[0];
				$relativeModelWithAttributeTo = $relativeModelWithOptions[1];
			}

			$relativeContentItemValues = [];
			foreach ($contentItemValues as $contentItemValue) {
				$relativeModelAttributeValue = $contentItemValue[$relativeValueKeyName];

				/* @var $relativeModelQuery ActiveQuery */
				$relativeModelQuery = $relativeModelClassName::find();

				$relativeModelQuery = $relativeModelQuery->where([
					$relativeModelAttributeName => $relativeModelAttributeValue
				]);

				$relativeModelQuery = (isset($relativeModelWithAttributeTo) && isset($relativeModelWithId)) ?
					$relativeModelQuery
						->select($relativeModelWithAttributeTo)
						->with($relativeModelWithId) :
					$relativeModelQuery
						->select($dbForeignAttributeTo);

				/* @var $relativeModel ActiveRecord */
				$relativeModel = $relativeModelQuery->one();

				$relativeModelWithValue = (isset($relativeModelWithId)) ? ArrayHelper::getValue($relativeModel, $relativeModelWithId) : '';
				$relativeModel = (isset($relativeModelWithId)) ?
					((!empty($relativeModelWithValue)) ? $relativeModelWithValue : '') : $relativeModel;

				if (empty($relativeModel)) {
					$message = 'Model with class name "' . $relativeModelClassName . '" and "'.$relativeModelAttributeName.'"="'.$relativeModelAttributeValue.'" ';
					$message .= (isset($relativeModelWithId)) ? ' with relative value id "'.$relativeModelWithId.'" ' : '';
					$message .= 'not exist...' . PHP_EOL;
					$this->stdout($message, Console::FG_YELLOW);
					continue;
				}

				$relativeModelInsertValue = $relativeModel->getAttribute($dbForeignAttributeTo);

				foreach ($contentItemValue['values'] as $value) {
					$value[$dbForeignAttributeFrom] = $relativeModelInsertValue;
					$relativeContentItemValues[] = $value;
				}
			}

			return $relativeContentItemValues;
		}
	}

	protected function sanitizeContentItemValue($contentItemValue) {
		$relativeModelClassName = ArrayHelper::getValue($contentItemValue, 'relativeModelClassName');
		$dbForeignAttributeTo = ArrayHelper::getValue($contentItemValue, 'dbForeignAttributeTo');
		$relativeModelAttribute = ArrayHelper::getValue($contentItemValue, 'relativeModelAttribute');

		if (empty($relativeModelClassName) && empty($dbForeignAttributeTo) && empty($relativeModelAttribute)) {
			return $contentItemValue;
		} else {
			/* @var $relativeModelQuery ActiveQuery */
			$relativeModelQuery = $relativeModelClassName::find();
			$relativeModelQuery = $relativeModelQuery->where($relativeModelAttribute);
			$relativeModelQuery = $relativeModelQuery->select($dbForeignAttributeTo);

			/* @var $relativeModel ActiveRecord */
			$relativeModel = $relativeModelQuery->one();

			if (empty($relativeModel)) {
				$message = 'Model with class name "' . $relativeModelClassName . '" and ';
				foreach ($relativeModelAttribute as $relativeModelAttributeName => $relativeModelAttributeValue) {
					$message .= '"'.$relativeModelAttributeName.'"="'.$relativeModelAttributeValue.'" ';
				}
				$message .= 'not exist...' . PHP_EOL;
				$this->stdout($message, Console::FG_YELLOW);

				return '';
			}

			return $relativeModel->getAttribute($dbForeignAttributeTo);
		}
	}

	/* ************************ACTION PROTECTED FUNCTION*************************************** */

	protected function reImportContentItem($contentItem) {
		$demoContentValues = $this->sanitizeContentItemValues($contentItem);
		$demoContentModelClassName = ArrayHelper::getValue($contentItem, 'modelClassName');
		$this->deleteContent($demoContentModelClassName);

		$demoContentModels = $this->generateModelsFromArray($demoContentValues, $demoContentModelClassName);

		return $this->saveModels($demoContentModels);
	}

	protected function deleteContentItem($contentItem) {
		$demoContentValues = $this->sanitizeContentItemValues($contentItem);
		$demoContentModelClassName = $contentItem['modelClassName'];

		return $this->deleteDemoContent($demoContentValues, $demoContentModelClassName);
	}

	protected function importContentItem($contentItem) {
		$demoContentValues = $this->sanitizeContentItemValues($contentItem);
		$demoContentModelClassName = $contentItem['modelClassName'];

		$demoContentModels = $this->generateModelsFromArray($demoContentValues, $demoContentModelClassName);

		return $this->saveModels($demoContentModels);
	}

	/* ************************DATA PROCESS PROTECTED FUNCTION*************************************** */

	protected function deleteContent($modelClassName) {
		if (in_array($modelClassName, $this->deletedModels)) {
			return true;
		}
		$this->stdout('All Models with class name "' . $modelClassName . '" deleted...' . PHP_EOL, Console::FG_YELLOW);
		$this->deletedModels[] = $modelClassName;

		/* @var $modelClassName ActiveRecord */
		return $modelClassName::deleteAll();
	}

	protected function deleteDemoContent($array, $modelClassName) {
		foreach ($array as $item) {
			/* @var $existModelsQuery ActiveQuery */
			$existModelsQuery = $modelClassName::find()->where($item);

			if ($existModelsQuery->exists()) {
				$existModels = $existModelsQuery->all();
				foreach ($existModels as $existModel) {
					/* @var $existModel ActiveRecord */
					$existModel->delete();
					$this->stdout('Model with id #' . $existModel->id . ' deleted...' . PHP_EOL, Console::FG_YELLOW);
				}
			}
			else {
				$this->stdout('Model with data not exist...' . PHP_EOL, Console::FG_YELLOW);
			}
		}

		return 1;
	}

	protected function generateModelsFromArray($array, $modelClassName) {
		$models = [];
		foreach ($array as $item) {
			/* @var $existModelQuery ActiveQuery */
			$existModelQuery = $modelClassName::find()->where($item);

			if ($existModelQuery->exists()) {
				$existModel = $existModelQuery->one();
				$this->stdout('Model exist with id #' . $existModel->id . '...' . PHP_EOL, Console::FG_YELLOW);
				continue;
			}

			/* @var $model ActiveRecord */
			$model = new $modelClassName;
			$model->loadDefaultValues();
			$model->attributes = $item;

			if ($model->validate()) {
				$models[] = $model;
			} else {
				$this->stdout('Errors while validate model...' . PHP_EOL, Console::FG_RED);
				$this->stdout('Model data:' . PHP_EOL, Console::FG_YELLOW);
				foreach ($model->attributes() as $modelAttributeKey) {
					$value = (string) $model->getAttribute($modelAttributeKey);
					$maxCharOut = self::ATTRIBUTE_MAX_CHAR_COUNT;
					if (strlen($value) > $maxCharOut)
						$value = substr($value, 0, $maxCharOut) . '...';

					$this->stdout('"'.$modelAttributeKey.'": ' . $value . PHP_EOL, Console::FG_YELLOW);
				}

				$this->stdout('Errors data...' . PHP_EOL, Console::FG_RED);
				foreach ($model->errors as $modelErrorKey => $modelErrorMessages) {
					$attributeErrors = '';
					foreach ($modelErrorMessages as $modelErrorMessage) {
						$attributeErrors .= '- ' .$modelErrorMessage . PHP_EOL;
					}
					$this->stdout('"'.$modelErrorKey.'":' . PHP_EOL . $attributeErrors, Console::FG_RED);
				}
				echo PHP_EOL;
			}
		}

		return $models;
	}

	protected function saveModels($models) {
		if (empty($models)) {
			$this->stdout('No models for saving...' . PHP_EOL, Console::FG_RED);

			return false;
		}

		$rows = ArrayHelper::getColumn($models, 'attributes');

		/* @var $model ActiveRecord */
		$model = $models[0];
		$modelAttributes = $model->attributes();
		$model->getDb()->createCommand()->batchInsert($model::tableName(), $modelAttributes, $rows)->execute();

		$maxRowOut = self::TABLE_MAX_ROW_COUNT;
		$maxCharOut = self::TABLE_MAX_COLUMN_CHAR_COUNT;

		$rowValues = [];
		foreach ($rows as $rowIndex => $row) {
			$rowData = array_values($row);
			if (empty($rowData[0])) {
				$rowData[0] = $rowIndex+1;
			}

			if (count($rowData) > $maxRowOut) {
				$rowData = array_slice($rowData, 0, $maxRowOut);
			}

			foreach ($rowData as $rowItemKey => $rowItemValue) {
				if (strlen($rowItemValue) > $maxCharOut)
					$rowData[$rowItemKey] = substr($rowItemValue, 0, $maxCharOut) . '...';
			}

			$rowValues[] = $rowData;
		}

		if (count($modelAttributes) > $maxRowOut) {
			$modelAttributes = array_slice($modelAttributes, 0, $maxRowOut);
		}

		echo Table::widget([
			'headers' => $modelAttributes,
			'rows' => $rowValues
		]);

		$this->stdout('Saving Complete...' . PHP_EOL, Console::FG_GREEN);

		return 1;
	}
}