<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pessoa".
 *
 * @property integer $id
 * @property string $dsc_pessoa
 * @property string $num_cpf_pessoa
 * @property integer $id_tipo_pessoa
 *
 * @property Anotacao[] $anotacaos
 * @property AudienciaPessoa[] $audienciaPessoas
 * @property Audiencia[] $idAudiencias
 * @property TipoPessoa $idTipoPessoa
 */
class Pessoa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pessoa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dsc_pessoa', 'id_tipo_pessoa'], 'required'],
            [['id_tipo_pessoa'], 'integer'],
            [['dsc_pessoa'], 'string', 'max' => 100],
            [['num_cpf_pessoa'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dsc_pessoa' => 'Dsc Pessoa',
            'num_cpf_pessoa' => 'Num Cpf Pessoa',
            'id_tipo_pessoa' => 'Id Tipo Pessoa',
        ];
    }
	
    
    public function getLi()
    {
    	$tipoPessoa = $this->getIdTipoPessoa()->one();
    	return '<li id="liItemPessoa'.$this->id.'"class="list-group-item">
    					<span class="col-lg-10">
    						'.$this->dsc_pessoa.'
    						 - '.$tipoPessoa->dsc_tipo_pessoa.'
    					</span>
    						<a class="btn btn-default" onclick="removePessoa('.$this->id.')">
    										<i class="glyphicon glyphicon-trash"></i>
    						</a>
    					</li>';
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnotacaos()
    {
        return $this->hasMany(Anotacao::className(), ['id_pessoa' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAudienciaPessoas()
    {
        return $this->hasMany(AudienciaPessoa::className(), ['id_pessoa' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAudiencias()
    {
        return $this->hasMany(Audiencia::className(), ['id' => 'id_audiencia'])->viaTable('audiencia_pessoa', ['id_pessoa' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoPessoa()
    {
        return $this->hasOne(TipoPessoa::className(), ['id' => 'id_tipo_pessoa']);
    }
}
