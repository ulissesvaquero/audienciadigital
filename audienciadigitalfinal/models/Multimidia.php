<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "multimidia".
 *
 * @property integer $id
 * @property string $dsc_arquivo
 * @property integer $id_audiencia
 * @property integer $flg_merge
 * @property string $dsc_caminho_completo
 *
 * @property Audiencia $idAudiencia
 */
class Multimidia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'multimidia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_audiencia'], 'required'],
            [['id_audiencia'], 'integer'],
            [['dsc_arquivo'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dsc_arquivo' => 'Dsc Arquivo',
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
    
    public function getRealPath()
    {
    	$url = Url::to(['../upload'],true);
    	return '../../upload/'.$this->id_audiencia.'/'.$this->dsc_arquivo;
    	return $url.'/'.$this->id_audiencia.'/'.$this->dsc_arquivo;
    }
}
