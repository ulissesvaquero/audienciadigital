<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "juiz".
 *
 * @property integer $id
 * @property string $dsc_nome
 * @property string $num_cpf
 * @property string $dsc_img
 *
 * @property Audiencia[] $audiencias
 */
class Juiz extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'juiz';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dsc_nome'], 'required'],
            [['dsc_img'], 'string'],
            [['dsc_nome'], 'string', 'max' => 100],
            [['num_cpf'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dsc_nome' => 'Dsc Nome',
            'num_cpf' => 'Num Cpf',
            'dsc_img' => 'Dsc Img',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAudiencias()
    {
        return $this->hasMany(Audiencia::className(), ['id_juiz' => 'id']);
    }
}
