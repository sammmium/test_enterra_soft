<?php

namespace src\Models;

use src\models\Base;
use src\Traits\Helper;

require_once 'Base.php';
require_once __DIR__ . '/../Traits/Helper.php';

class News extends Base
{
	private string $table = 'news';

	protected array $availableColumns = [
		'title',
		'description',
		'value',
		'author_id',
		'create_at',
		'update_at'
	];

    use Helper;

    public function createNews()
    {
        
    }

    public function storeNews()
    {
        
    }

    public function editNews(int $id)
    {
        
    }

    public function deleteNews(int $id)
    {
        
    }

    public function listNews()
    {
        $result = [];

        $query = "select
                n.id as id,
                n.title as title,
                n.description as description,
                n.value as value,
                concat(u.first_name, ' ', u.last_name) as author,
                u.role as role,
                n.create_at as create_at,
                n.update_at as update_at
			from " . $this->table . " as n
            left join users as u on n.author_id = u.id
			order by n.create_at desc;";

        $rawData = $this->all($query);

        if ($rawData) {
            foreach ($rawData as $item) {
                $item['create_at'] = self::transformDate($item['create_at'], 'ru');
                $item['update_at'] = self::transformDate($item['update_at'], 'ru');
                $result[] = $item;
            }
        }
        
        return $result;
    }

    public function getNews(int $id)
    {
        $result = [];
        $filter = ['n.id' => $id];

        $query = "select
                n.id as id,
                n.title as title,
                n.description as description,
                n.value as value,
                concat(u.first_name, ' ', u.last_name) as author,
                u.role as role,
                n.create_at as create_at,
                n.update_at as update_at
            from " . $this->table . " as n
            left join users as u on n.author_id = u.id
			where " . $this->getPreparedConditions($filter) . " 
            limit 1;";

        $rawData = $this->get($query);

        if ($rawData) {
            $rawData['create_at'] = self::transformDate($rawData['create_at'], 'ru');
            $rawData['update_at'] = self::transformDate($rawData['update_at'], 'ru');
            $result = $rawData;
        }

        return $result;
    }
}
