<?php

namespace order\repositories;

use order\components\Params;
use order\components\Table\ColumnsHeader;
use order\models\Orders;
use order\models\OrdersSearch;
use order\models\Services;
use order\models\Users;
use order\Module;
use stdClass;
use Yii;
use yii\db\Query;

/**
 * Репозиторий для получения информации по заказам для таблицы
 */
class OrdersRepository
{
    /** @var Query $query Запрос для получения данных */
    public Query $query;

    /** @var Params $params GET параметры для фильтрации таблицы */
    public Params $params;

    /** @var int Лимит количества строк */
    public int $limit = 100;

    /**
     * Преобразование параметров в объект и фильтрация от пустых значений
     *
     * @param array $params
     * @return OrdersRepository
     */
    public function setParams(array $params = []): self
    {
        $this->params = new Params();
        $filteredParams = array_filter($params, function ($value) {
            return $value !== null && $value !== '';
        });

        foreach ($filteredParams as $key => $value) {
            $this->params->{$key} = match ($key) {
                'status' => array_search(ucfirst($value), Orders::getStatusList()),
                'mode' => array_search(ucfirst($value), Orders::getModeList()),
                default => $value
            };
        }

        return $this;
    }

    /**
     * Получения результат для таблицы
     *
     * @return stdClass
     */
    public function result(): stdClass
    {
        $result = new stdClass();
        $result->data = $this->getData();
        $result->total = $this->getTotal();
        $result->columns = $this->getColumns();
        $result->footer = new stdClass();
        $result->footer->start = 1;
        $result->footer->end = $this->limit;

        return $result;
    }

    /**
     * Базовый запрос для табличного представления Заказов
     *
     * @return $this
     */
    public function query(): static
    {
        $this->query = (new Query())
            ->select([
                'id' => 'orders.id',
                'user' => 'concat(users.first_name, " ", users.last_name)',
                'link' => 'orders.link',
                'quantity' => 'orders.quantity',
                'service' => 'services.name',
                'status' => 'orders.status',
                'created' => "orders.created_at",
                'mode' => 'orders.mode',
            ])
            ->from(Orders::tableName())
            ->innerJoin(Services::tableName(), 'services.id = orders.service_id')
            ->innerJoin(Users::tableName(), 'users.id = orders.user_id');

        if (isset($this->params->status)) $this->query->andWhere(['orders.status' => $this->params->status]);

        if (isset($this->params->search) && isset($this->params->searchType)) {
            match ($this->params->searchType) {
                OrdersSearch::ID => $this->query->andWhere(['orders.id' => $this->params->search]),
                OrdersSearch::LINK => $this->query->andWhere(['like', 'orders.link', $this->params->search]),
                OrdersSearch::USER => $this->query->orWhere(['like', 'concat(users.first_name, " ", users.last_name)', $this->params->search]),
                default => throw new \Exception('Unexpected match value'),
            };
        }

        if (isset($this->params->service)) $this->query->andWhere(['services.id' => $this->params->service]);
        if (isset($this->params->mode)) $this->query->andWhere(['orders.mode' => $this->params->mode]);

        return $this;
    }

    /**
     * Получение данных из БД от запроса {@see OrdersRepository::$query}
     * и преобразование данных
     *
     * @return array
     */
    public function getData(): array
    {
        $orders = [];

        foreach ($this
            ->query
            ->offset(($this->params->page - 1) * $this->limit)
            ->limit($this->limit)
            ->each() as $order) {
            $order['status'] = Yii::t(Module::I18N_CATEGORY, Orders::getStatusList()[$order['status']]);
            $order['mode'] = Yii::t(Module::I18N_CATEGORY, Orders::getModeList()[$order['mode']]);
            $orders[] = $order;
        }

        return $orders;
    }

    /**
     * Получение общего кол-ва заказов с применёнными фильтрами
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->query->count();
    }

    /**
     * Получение запроса для получения CSV
     *
     * @return Query
     */
    public function getExportQuery(): Query
    {
        return $this->query;
    }

    /**
     * Получение колонок для таблицы
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            new ColumnsHeader('id', Module::I18N_CATEGORY, ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader('user', Module::I18N_CATEGORY, ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader('link', Module::I18N_CATEGORY, ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader('quantity', Module::I18N_CATEGORY, ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader('service',
                Module::I18N_CATEGORY,
                ColumnsHeader::COLUMN_DROPDOWN,
                ServicesRepository::getServicesForFilter()
            ),
            new ColumnsHeader('status', Module::I18N_CATEGORY, ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader('mode',
                Module::I18N_CATEGORY,
                ColumnsHeader::COLUMN_DROPDOWN,
                ModesRepository::getModesForFilter()
            ),
            new ColumnsHeader('created', Module::I18N_CATEGORY, ColumnsHeader::COLUMN_STRING),
        ];
    }


}