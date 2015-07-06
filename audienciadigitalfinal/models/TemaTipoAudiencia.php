<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tema_tipo_audiencia".
 *
 * @property integer $id_tema
 * @property integer $id_tipo_audiencia
 *
 * @property Tema $idTema
 * @property TipoAudiencia $idTipoAudiencia
 */
class TemaTipoAudiencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tema_tipo_audiencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tema', 'id_tipo_audiencia'], 'required'],
            [['id_tema', 'id_tipo_audiencia'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tema' => 'Id Tema',
            'id_tipo_audiencia' => 'Id Tipo Audiencia',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTema()
    {
        return $this->hasOne(Tema::className(), ['id' => 'id_tema']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoAudiencia()
    {
        return $this->hasOne(TipoAudiencia::className(), ['id' => 'id_tipo_audiencia']);
    }
}
