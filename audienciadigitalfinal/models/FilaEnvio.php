<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fila_envio".
 *
 * @property integer $id
 * @property string $id_registro
 * @property string $tabela
 * @property integer $flg_enviado
 */
class FilaEnvio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fila_envio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['flg_enviado','id_registro'], 'integer'],
            [['tabela'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_registro' => 'Id Registro',
            'tabela' => 'Tabela',
            'flg_enviado' => 'Flg Enviado',
        ];
    }
}
