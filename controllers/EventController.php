<?php

namespace app\controllers;

use app\models\forms\MapForm;
use app\models\forms\EventListForm;
use app\models\Pass;
use Yii;
use app\models\Event;
use app\models\EventSearch;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Link;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller
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
                    [
                        'allow' => true,
                        'actions' => ['map'],
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Event models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventSearch([
            'start_date' => date('Y-m-d'),
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Event model.
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
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Event();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @param int $selectedPassId Optional. Id of a Pass to be displayed for modification alongside the Event.
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $selectedPassId = 0)
    {
        $event = $this->findModel($id);
        if ($event->load(Yii::$app->request->post())) {
            $event->save();
        }

        $newLink = new Link(['event_id' => $id]);
        if ($newLink->load(Yii::$app->request->post()) && !$newLink->isEmpty() && $newLink->save()) {
            $newLink = new Link(['event_id' => $id]); // Clear inputs after saving
        }

        $pass = Pass::findOne($selectedPassId);
        if (!$pass) {
            $pass = new Pass(['event_id' => $id]);
        }

        $prices = $pass->generatePriceList();
        if (Model::loadMultiple($prices, Yii::$app->request->post()) && !$pass->hasErrors()) {
            if ($pass->updatePriceList($prices)) {
                $prices = $pass->generatePriceList();
            }
        }

        if ($pass->load(Yii::$app->request->post()) && $pass->save()) {
            if (!$selectedPassId) {
                $this->redirect(['update', 'id' => $id,'selectedPassId' => $pass->id]);
            }
        }

        $this->layout = 'fluid';
        return $this->render('update', [
            'event' => $event,
            'newLink' => $newLink,
            'pass' => $pass,
            'prices' => $prices,
        ]);
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Shows a map-based event search page.
     * @return string
     */
    public function actionMap()
    {
        $mapForm = new MapForm([
            'from_date' => date('Y-m-d'),
        ]);
        
        $mapForm->load(Yii::$app->request->post());
        $mapForm->validate();
        
        $this->layout = 'fluid';
        return $this->render('map', [
            'mapForm' => $mapForm,
        ]);
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
