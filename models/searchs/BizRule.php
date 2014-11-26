<?php

namespace yii2mod\rbac\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii2mod\rbac\models\BizRule as MBizRule;

/**
 * Description of BizRule
 *
 * @author MDMunir
 */
class BizRule extends Model
{
    /**
     * @var string name of the rule
     */
    public $name;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'safe']
        ];
    }

    /**
     *
     * @param array $params
     *
     * @return \yii\data\ActiveDataProvider|\yii\data\ArrayDataProvider
     */
    public function search($params)
    {
        /* @var \yii\rbac\Manager $authManager */
        $authManager = Yii::$app->authManager;
        $models = [];
        $included = !($this->load($params) && $this->validate() && trim($this->name) !== '');
        foreach ($authManager->getRules() as $name => $item) {
            if ($included || stripos($item->name, $this->name) !== false) {
                $models[$name] = new MBizRule($item);
            }
        }
        return new ArrayDataProvider([
            'allModels' => $models,
        ]);
    }
}