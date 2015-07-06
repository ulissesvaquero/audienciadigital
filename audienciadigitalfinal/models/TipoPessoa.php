<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_pessoa".
 *
 * @property integer $id
 * @property string $dsc_tipo_pessoa
 *
 * @property Pessoa[] $pessoas
 */
class TipoPessoa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_pessoa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dsc_tipo_pessoa'], 'required'],
            [['dsc_tipo_pessoa'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dsc_tipo_pessoa' => 'Dsc Tipo Pessoa',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoas()
    {
        return $this->hasMany(Pessoa::className(), ['id_tipo_pessoa' => 'id']);
    }
}
