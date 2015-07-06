<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "audiencia_pessoa".
 *
 * @property integer $id_audiencia
 * @property integer $id_pessoa
 *
 * @property Audiencia $idAudiencia
 * @property Pessoa $idPessoa
 */
class AudienciaPessoa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'audiencia_pessoa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_audiencia', 'id_pessoa'], 'required'],
            [['id_audiencia', 'id_pessoa'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_audiencia' => 'Id Audiencia',
            'id_pessoa' => 'Id Pessoa',
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
    public function getIdPessoa()
    {
        return $this->hasOne(Pessoa::className(), ['id' => 'id_pessoa']);
    }
}
