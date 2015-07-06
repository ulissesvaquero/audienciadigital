<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tema_audiencia".
 *
 * @property integer $id_tema
 * @property integer $id_audiencia
 *
 * @property Audiencia $idAudiencia
 * @property Tema $idTema
 */
class TemaAudiencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tema_audiencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tema', 'id_audiencia'], 'required'],
            [['id_tema', 'id_audiencia'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tema' => 'Id Tema',
            'id_audiencia' => 'Id Audiencia',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAudiencia()
    {
        return $this->hasOne(Audiencia::className(), ['id' => 'id_audiencia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTema()
    {
        return $this->hasOne(Tema::className(), ['id' => 'id_tema']);
    }
}
