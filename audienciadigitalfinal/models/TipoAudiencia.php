<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_audiencia".
 *
 * @property integer $id
 * @property string $dsc_tipo_audiencia
 * @property integer $flg_num_processo
 *
 * @property Audiencia[] $audiencias
 */
class TipoAudiencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_audiencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dsc_tipo_audiencia'], 'required'],
            [['flg_num_processo'], 'integer'],
            [['dsc_tipo_audiencia'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dsc_tipo_audiencia' => 'Dsc Tipo Audiencia',
            'flg_num_processo' => 'Flg Num Processo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAudiencias()
    {
        return $this->hasMany(Audiencia::className(), ['id_tipo_audiencia' => 'id']);
    }
}
