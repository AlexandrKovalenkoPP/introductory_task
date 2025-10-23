<?php

namespace app\repositories;

use app\components\Table\ColumnsHeader;
use app\modules\order\models\Orders;
use app\modules\order\models\Services;
use app\modules\order\models\Users;
use stdClass;
use yii\db\Query;

/**
 * Репозиторий для получения информации по заказам для таблицы
 */
class OrdersRepository
{
    /** @var Query $query Запрос для получения данных */
    public Query $query;

    /** @var stdClass $params GET параметры для фильтрации таблицы */
    public stdClass $params;

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
        $filteredParams = array_filter($params, function ($value) {
            return $value !== null && $value !== '';
        });

        $this->params = (object) $filteredParams;

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
        $result->serviceList = ServicesRepository::getServicesForFilter();
        $result->modeList = Orders::getModeList();

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
                Orders::getLocationId() => 'orders.id',
                Orders::getLocationUser() => 'concat(users.first_name, " ", users.last_name)',
                Orders::getLocationLink() => 'orders.link',
                Orders::getLocationQuantity() => 'orders.quantity',
                Orders::getLocationServiceId() => 'services.name',
                Orders::getLocationStatus() => 'orders.status',
                Orders::getLocationCreatedAt() => "orders.created_at",
                Orders::getLocationMode() => 'orders.mode',
            ])
            ->from(Orders::tableName())
            ->innerJoin(Services::tableName(), 'services.id = orders.service_id')
            ->innerJoin(Users::tableName(), 'users.id = orders.user_id');

        if (isset($this->params->status)) $this->query->andWhere(['orders.status' => $this->params->status]);

        if (isset($this->params->search)) {
            match ($this->params->searchType) {
                'id' => $this->query->andWhere(['orders.id' => $this->params->search]),
                'link' => $this->query->andWhere(['like', 'orders.link', $this->params->search]),
                'user' => $this->query->orWhere(['like', 'concat(users.first_name, " ", users.last_name)', $this->params->search]),
            };
        }

        if (isset($this->params->service_id)) $this->query->andWhere(['services.id' => $this->params->service_id]);
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
        $orders = $this
            ->query
            ->offset(($this->params->page - 1) * $this->limit)
            ->limit($this->limit)
            ->all();

        foreach ($orders as $key => $value) {
            $orders[$key][Orders::getLocationStatus()] = Orders::getStatusList()[$value[Orders::getLocationStatus()]];
            $orders[$key][Orders::getLocationMode()] = Orders::getModeList()[$value[Orders::getLocationMode()]];

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
            new ColumnsHeader(Orders::getLocationId(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationUser(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationLink(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationQuantity(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationServiceId(), ColumnsHeader::COLUMN_DROPDOWN),
            new ColumnsHeader(Orders::getLocationStatus(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationMode(), ColumnsHeader::COLUMN_DROPDOWN),
            new ColumnsHeader(Orders::getLocationCreatedAt(), ColumnsHeader::COLUMN_STRING),
        ];
    }


}