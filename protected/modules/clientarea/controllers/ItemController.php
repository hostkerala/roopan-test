<?php

class ItemController extends ClientareaController {

    const LIST_ACTION_ROUTE = '/clientarea/item/list';

    public function filters() {
        $filters = parent::filters();
        // add ajax filter for all requests
        //	$filters[] = 'ajaxRequest';

        return $filters;
    }


    /**
     * load UI for item section (left menu and action buttons)
     *
     * @param int $categoryType Category type to show UI section
     */
    public function actionIndex($categoryType = Category::TYPE_DEFAULT) {
        $categories = Category::model()->findAllByAttributes(array(
            Category::ATTR_LEVEL => 0,
            'category_type' => $categoryType
        ));

        $this->render('index', array(
            'categories_root' => $categories,
            'categoryType' => $categoryType
        ));
    }

    /**
     * ajax action to load items list
     */
    public function actionList($categoryType = Category::TYPE_DEFAULT) {
        $categories_tree = Category::model()->findAllByAttributes(
            array('category_type' => $categoryType),
            array('order' => Category::ATTR_LEFT . ' ASC')
        );

        $this->render('list', array(
            'categories_tree' => $categories_tree,
        ));
    }

    /**
     * item create form, category id os required
     * @param integer $category_id
     */
    public function actionCreate($category_id) {
        $category = Category::model()->findByPk($category_id);
        if (empty($category)) {
            throw new CHttpException(400, 'Bad request.');
        }

        $item = new Item('create');
        $item->item_category_id = $category->id;
        $client = null;
        $additional_data = array();

        if (!empty($_POST['Item'])) {
            $item->attributes = $_POST['Item'];
            $client = $item->getClientByInput($_POST['Item']);
            $additional_data = $_POST['Item']['ItemAdditional'];

            // check item valid - then we can analyze item & client
            if ($item->validate() && $client->validate() && $this->additionalDataValidate($additional_data)) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $this->saveItemModel($item, $client, $additional_data);
                    $transaction->commit();

                    // notify user that all is okay and load list again
                    Yii::app()->user->setFlash('success', 'Item has been saved');
                    $this->renderJSON(array('refreshBlock', '#item-content', Html::url(self::LIST_ACTION_ROUTE)));
                } catch (Exception $e) { // an exception is raised if a query fails
                    $transaction->rollback();
                    //$item->addError('item_client_name', 'Database error: '.$e->getMessage());
                    Yii::app()->user->setFlash('error', 'Database error, please try again.');
                }
            }
        }

        $this->render('create', array('model' => $item, 'client' => $client, 'category' => $category, 'additional_data' => $additional_data));
    }

    /**
     * save item model, check that client exists before that.
     * @param Item $item model to update
     * @param Client $client  model for client record to save
     */
    protected function saveItemModel($item, $client, $itemAdditional) {
        if (!$item->validate())
            return false;

        if (empty($item->item_client_id) && !empty($item->item_client_name)) {
            // create client
            $client->client_user_id = Yii::app()->user->id;
            $client->save();

            // update item
            $item->item_client_id = $client->id;
            $item->item_client_name = $client->client_name;
            $item->item_name = $client->client_name;
        } else {
            $client->save();
        }

        // update item
        if ($item->save(false)) {
            $itemErrors = array();
            foreach ($itemAdditional as $_item) {

                $id = isset($_item['id']) ? $_item['id'] : 0;
                if (isset($_item['id'])) {
                    unset($_item['id']);
                }
                $values = array_filter($_item);

                if (empty($values)) {
                    if ($id !== 0) {
                        $itemModel = ItemAdditional::model()->findByPk($id);
                        $itemModel->delete();
                    }
                    continue;
                }
                if ($id) {
                    $itemModel = ItemAdditional::model()->findByPk($id);
                    $itemModel->attributes = $_item;
                    if ($itemModel->validate()) {
                        $itemModel->save();
                    } else {
                        $itemErrors = $itemModel->errors;
                        break;
                    }
                } else {
                    $itemModel = new ItemAdditional();
                    $itemModel->attributes = $_item;
                    $itemModel->item_id = $item->id;
                    if ($itemModel->validate()) {
                        $itemModel->save();
                    } else {
                        $itemErrors = $itemModel->errors;
                        break;
                    }
                }
            }
            //if(empty($itemErrors)){
            return true;
            //}
        }
        return false;
    }

    protected function additionalDataValidate($itemAdditional) {

        $itemErrors = array();
        foreach ($itemAdditional as $_item) {
            $id = isset($_item['id']) ? $_item['id'] : 0;
            if (isset($_item['id'])) {
                unset($_item['id']);
            }
            $values = array_filter($_item);
            if (empty($values)) {
                continue;
            }
            if ($id) {
                $itemModel = ItemAdditional::model()->findByPk($id);
                $itemModel->attributes = $_item;
                if (!$itemModel->validate()) {
                    $itemErrors[] = $itemModel->errors;
                }
            } else {
                $itemModel = new ItemAdditional();
                $itemModel->attributes = $_item;

                if (!$itemModel->validate()) {
                    $itemErrors[] = $itemModel->errors;
                }
            }
        }
        if (!empty($itemErrors)) {
            return false;
        }
        return true;
    }

    /**
     * load data provider for given ids and print models
     */
    public function actionBulkView() {
        if (empty($_POST['ids']) || !is_array($_POST['ids'])) {
            throw new CHttpException(400, 'Bad request.');
        }

        $model = new Item('search');
        $model->unsetAttributes();
        $model->id = $_POST['ids'];

        $data_provider = $model->search();
        $data_provider->pagination = false;

        $this->render('bulkView', array('data_provider' => $data_provider));
    }

    /**
     * delete items
     */
    public function actionBulkDelete() {
        if (empty($_POST['ids']) || !is_array($_POST['ids'])) {
            throw new CHttpException(400, 'Bad request.');
        }

        Item::model()->deleteByPk($_POST['ids']);

        Yii::app()->user->setFlash('success', 'Item(s) has been deleted');
        $this->renderJSON(array('refreshBlock', '#item-content', Html::url(self::LIST_ACTION_ROUTE)));
    }

    /**
     * bulk update
     */
    public function actionBulkUpdate() {
        if ((empty($_POST['ids']) || !is_array($_POST['ids'])) && empty($_POST['Item'])) {
            throw new CHttpException(400, 'Bad request.');
        }

        $items = array();
        $clients = array();
        $additional_data = array();
        if (!empty($_POST['ids'])) {
            $items = Item::model()->findAllByAttributes(array('id' => $_POST['ids']));
            // hotfix: client names are empty in most of the record, we will try to fill them up with item_name instead
            foreach ($items as $item_id => $item) {
                if (empty($item->item_client_id)) {
                    $item->item_client_name = $item->item_name;
                    $clients[$item_id] = new Client();
                } else {
                    $clients[$item_id] = $item->client;
                }
                $_additional_data[$item_id] = ItemAdditional::model()->findAll('item_id=:item_id', array(':item_id' => $item->id));
            }
            if (!empty($_additional_data)) {
                foreach ($_additional_data as $item_id => $row) {
                    foreach ($row as $item) {
                        foreach ($item as $key => $val) {
                            if ($key == 'item_id') {
                                continue;
                            }
                            $additional_data[$item_id][$item->item_id][$item->id][$key] = $val;
                        }
                    }
                }
            }
        }

        if (!empty($_POST['Item'])) {
            $is_valid = true;
            $items = array();
            $clients = array();
            $additional_data = array();
            foreach ($_POST['Item'] as $id => $input) {
                $item = Item::model()->findByPk($id);
                $item->attributes = $input;
                $client = $item->getClientByInput($input);
                //		pa(array($item->attributes, $item->item_client_name));
                $is_valid &= $item->validate() && $client->validate() && $this->additionalDataValidate($input['ItemAdditional']);
                $items[$id] = $item;
                $clients[$id] = $client;
                $additional_data[$id] = $input['ItemAdditional'];
            }

            // if all records valid - then save
            if ($is_valid) {
                // we have multiple save here - so using transactions
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    foreach ($items as $item_id => $item) {
                        $this->saveItemModel($item, $clients[$item_id], $additional_data[$item_id]);
                    }
                    $transaction->commit();

                    // notify user that all is okay and load list again
                    Yii::app()->user->setFlash('success', 'Items have been updated.');
                    $this->renderJSON(array('refreshBlock', '#item-content', Html::url(self::LIST_ACTION_ROUTE)));
                } catch (Exception $e) { // an exception is raised if a query fails
                    $transaction->rollback();
                    //$item->addError('item_client_name', 'Database error: '.$e->getMessage());
                    Yii::app()->user->setFlash('error', 'Database error, please try again.');
                }
            }
        }

        //pa($items,1);
        $this->render('bulkUpdate', array('items' => $items, 'clients' => $clients, 'additional_data' => $additional_data));
    }

    /**
     * Print items
     *
     * @throws CHttpException
     */
    public function actionPrint()
    {
        if (empty($_POST['ids']) || !is_array($_POST['ids'])) {
            throw new CHttpException(400, 'Bad request.');
        }

        $criteria = new CDbCriteria();
        $criteria->addInCondition('t.id', $_POST['ids']);
        $items = Item::model()->with('category', 'client', 'additional_data')->findAll($criteria);

        $this->render('print', array('items' => $items));
    }
}
