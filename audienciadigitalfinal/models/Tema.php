<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tema".
 *
 * @property integer $id
 * @property string $dsc_tema
 *
 * @property Anotacao[] $anotacaos
 * @property TemaAudiencia[] $temaAudiencias
 * @property Audiencia[] $idAudiencias
 */
class Tema extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tema';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dsc_tema'], 'required'],
            [['dsc_tema'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dsc_tema' => 'Dsc Tema',
        ];
    }
	
    
    public function getLi()
    {
    	return '<li id="liTema'.$this->id.'"class="list-group-item">
    					<span class="col-lg-10">
    						'.$this->dsc_tema.'
    					</span>
    						<a class="btn btn-default" onclick="removeTema('.$this->id.')">
    										<i class="glyphicon glyphicon-trash"></i>
    						</a>
    					</li>';
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnotacaos()
    {
        return $this->hasMany(Anotacao::className(), ['id_tema' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemaAudiencias()
    {
        return $this->hasMany(TemaAudiencia::className(), ['id_tema' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAudiencias()
    {
        return $this->hasMany(Audiencia::className(), ['id' => 'id_audiencia'])->viaTable('tema_audiencia', ['id_tema' => 'id']);
    }
}
