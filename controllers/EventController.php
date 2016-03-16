<?php

namespace app\controllers;

use app\behaviors\RememberLastPageBehavior;
use app\models\forms\MapForm;
use app\models\Pass;
use Yii;
use app\models\Event;
use app\models\EventSearch;
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
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $newLink = new Link(['event_id' => $id]);
        $newPass = new Pass(['event_id' => $id]);

        if ($newLink->load(Yii::$app->request->post())) {
            if ($newLink->save()) {
                // Clear new Link inputs so the user can add another new link
                $newLink = new Link(['event_id' => $id]);
            }
        }
        if ($newPass->load(Yii::$app->request->post())) {
            if ($newPass->save()) {
                // Clear new Pass inputs so the user can add another new Pass
                $newPass = new Pass(['event_id' => $id]);
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save() && !$newLink->hasErrors() && !$newPass->hasErrors()) {
                return $this->redirect($this->lastPage);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'newLink' => $newLink,
            'newPass' => $newPass,
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
     * @param array $selectionIds Array of the ids of the events to be shown in detail next to the map.
     * @return string
     */
    public function actionMap()
    {
        $model = new MapForm([
            'from_date' => date('Y-m-d'),
        ]);
        $model->load(Yii::$app->request->post());
        $model->load(Yii::$app->request->get());
        $model->validate();
        
        $events = Event::find()->fromMapForm($model)->all();

        return $this->render('map', [
            'model' => $model,
            'events' => $events,
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
