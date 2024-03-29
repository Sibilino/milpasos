<?php

namespace app\controllers;

use app\behaviors\RememberLastPageBehavior;
use Yii;
use app\models\Pass;
use app\models\PassSearch;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PassController implements the CRUD actions for Pass model.
 */
class PassController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'rememberUpdatedFrom' => [
                'class' => RememberLastPageBehavior::className(),
            ],
        ];
    }

    /**
     * Lists all Pass models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PassSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pass model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pass model and any related TemporaryPrices
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pass();
        $model->load(Yii::$app->request->post()) && $model->save();
        
        $prices = $model->generatePriceList();
        if (Model::loadMultiple($prices, Yii::$app->request->post()) && !$model->hasErrors()) {
            foreach ($prices as $price) {
                $price->save(); // Only true if all save() calls return true
            }
            $this->redirect(['update', 'id' => $model->getPrimaryKey()]);
        }

        return $this->render('update', [
            'model' => $model,
            'prices' => $prices,
        ]);
    }

    /**
     * Updates an existing Pass model and/or adds a new TemporaryPrice.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
        }

        $prices = $model->generatePriceList();
        if (Model::loadMultiple($prices, Yii::$app->request->post()) && !$model->hasErrors()) {
            if ($model->updatePriceList($prices)) {
                $prices = $model->generatePriceList();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'prices' => $prices,
        ]);
    }

    /**
     * Deletes an existing Pass model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect($this->lastPage);
    }

    /**
     * Finds the Pass model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Pass the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
