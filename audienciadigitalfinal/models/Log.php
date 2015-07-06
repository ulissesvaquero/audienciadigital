<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property integer $id
 * @property string $tabela
 * @property integer $id_registro
 * @property integer $id_usuario
 * @property string $acao
 * @property string $hora
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tabela'], 'required'],
            [['id_registro', 'id_usuario'], 'integer'],
            [['hora'], 'safe'],
            [['tabela'], 'string', 'max' => 45],
            [['acao'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tabela' => 'Tabela',
            'id_registro' => 'Id Registro',
            'id_usuario' => 'Id Usuario',
            'acao' => 'Acao',
            'hora' => 'Hora',
        ];
    }
}
