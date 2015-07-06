<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class UploadForm extends Model
{
	/**
	 * @var UploadedFile|Null file attribute
	 */
	public $audio;
	
	public $video;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
				[['audio','video'], 'file'],
		];
	}
}
