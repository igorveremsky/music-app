<?php

namespace app\modules\v1\models\elastic;

use Yii;

/**
 * This is the model class for search genres.
 *
 * @property int $id
 * @property string $name
 */
class GenreElastic extends \yii\elasticsearch\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

	public static function index()
	{
		return 'music-app-catalog';
	}

	public static function type()
	{
		return 'genres';
	}

	public function attributes()
	{
		return [
			'name',
		];
	}

	/**
	 * @return array This model's mapping
	 */
	public static function mapping()
	{
		return [
			static::type() => [
				"properties" => [
					'name' => ["type" => "string"],
				],
			],
		];
	}

	/**
	 * Set (update) mappings for this model
	 */
	public static function updateMapping()
	{
		$db = static::getDb();
		$command = $db->createCommand();
		$command->setMapping(static::index(), static::type(), static::mapping());
	}

	/**
	 * Create this model's index
	 */
	public static function createIndex()
	{
		$db = static::getDb();
		$command = $db->createCommand();


		$command->createIndex(static::index(), [
			//'settings' => [ /* ... */],
			'mappings' => static::mapping(),
			//'warmers' => [ /* ... */ ],
			//'aliases' => [ /* ... */ ],
			//'creation_date' => '...'
		]);
	}

	/**
	 * Delete this model's index
	 */
	public static function deleteIndex()
	{
		$db = static::getDb();
		$command = $db->createCommand();
		$command->deleteIndex(static::index(), static::type());
	}
}
