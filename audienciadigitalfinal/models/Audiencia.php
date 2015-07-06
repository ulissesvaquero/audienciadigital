<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "audiencia".
 *
 * @property integer $id
 * @property string $dsc_audiencia
 * @property string $nm_processo
 * @property integer $id_juiz
 * @property integer $id_tipo_audiencia
 * @property string $dat_inicio
 * @property string $dat_fim
 *
 * @property Anotacao[] $anotacaos
 * @property Juiz $idJuiz
 * @property TipoAudiencia $idTipoAudiencia
 * @property AudienciaPessoa[] $audienciaPessoas
 * @property Pessoa[] $idPessoas
 * @property Multimidia[] $multimidias
 * @property TemaAudiencia[] $temaAudiencias
 * @property Tema[] $idTemas
 */
class Audiencia extends \yii\db\ActiveRecord
{
	
	public $arrTema;
	
	public $arrPessoa;
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'audiencia';
    }
	
    public function getArrTema()
    {
    	$arrTema = $this->getIdTemas();
    	$arrTema = $arrTema->all();
    	$arrMapped = ArrayHelper::map($arrTema, 'id', 'dsc_tema');
    	return $arrMapped;
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_juiz', 'id_tipo_audiencia'], 'required'],
            [['id_juiz', 'id_tipo_audiencia'], 'integer'],
            [['dat_inicio', 'dat_fim'], 'safe'],
            [['dsc_audiencia'], 'string', 'max' => 100],
            [['nm_processo'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dsc_audiencia' => 'Dsc Audiencia',
            'nm_processo' => 'Nm Processo',
            'id_juiz' => 'Id Juiz',
            'id_tipo_audiencia' => 'Id Tipo Audiencia',
            'dat_inicio' => 'Dat Inicio',
            'dat_fim' => 'Dat Fim',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnotacaos()
    {
        return $this->hasMany(Anotacao::className(), ['id_audiencia' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdJuiz()
    {
        return $this->hasOne(Juiz::className(), ['id' => 'id_juiz']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoAudiencia()
    {
        return $this->hasOne(TipoAudiencia::className(), ['id' => 'id_tipo_audiencia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAudienciaPessoas()
    {
        return $this->hasMany(AudienciaPessoa::className(), ['id_audiencia' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPessoas()
    {
        return $this->hasMany(Pessoa::className(), ['id' => 'id_pessoa'])->viaTable('audiencia_pessoa', ['id_audiencia' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMultimidias()
    {
        return $this->hasMany(Multimidia::className(), ['id_audiencia' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemaAudiencias()
    {
        return $this->hasMany(TemaAudiencia::className(), ['id_audiencia' => 'id']);
    }
	
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTemas()
    {
    	return $this->hasMany(Tema::className(), ['id' => 'id_tema'])->viaTable('tema_audiencia', ['id_audiencia' => 'id']);
    }
}
