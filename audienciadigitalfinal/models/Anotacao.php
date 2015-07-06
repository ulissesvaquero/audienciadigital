<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "anotacao".
 *
 * @property integer $id
 * @property string $dsc_anotacao
 * @property float $num_tempo
 * @property integer $id_audiencia
 * @property integer $id_tema
 * @property integer $id_pessoa
 *  @property integer $id_usuario
 *
 * @property Audiencia $idAudiencia
 * @property Pessoa $idPessoa
 * @property Tema $idTema
 */
class Anotacao extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'anotacao';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_audiencia', 'id_tema', 'id_pessoa'], 'integer'],
            [['id_audiencia'], 'required'],
            [['dsc_anotacao'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dsc_anotacao' => 'Dsc Anotacao',
            'num_tempo' => 'Num Segundo',
            'id_audiencia' => 'Id Audiencia',
            'id_tema' => 'Id Tema',
            'id_pessoa' => 'Id Pessoa',
        ];
    }
    
    public function getLi()
    {
    	$m = Html::tag('i','',['class' => 'glyphicon glyphicon-time']).' '.
    			gmdate("H:i:s", $this->num_tempo).'<br>'.
    			Html::tag('i','',['class' => 'glyphicon glyphicon-list']).' '.
    			$this->getIdTema()->one()->dsc_tema.'<br>'.
    			Html::tag('i','',['class' => 'glyphicon glyphicon-user']).' '.
    			$this->getIdPessoa()->one()->dsc_pessoa;
    		
    	
    	return Html::tag('div',Html::a($m,null,['onclick' => 'goTo('.$this->num_tempo.')','class' => 'thumbnail']),['class' => 'col-lg-4']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTema()
    {
        return $this->hasOne(Tema::className(), ['id' => 'id_tema']);
    }
    
   
    
}
