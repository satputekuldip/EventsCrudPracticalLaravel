<?php

namespace App\Repositories;

use App\Models\Event;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class EventRepository
 * @package App\Repositories
 * @version April 19, 2021, 4:27 am UTC
*/

class EventRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'starts_at',
        'ends_at',
        'type',
        'banner',
        'venue',
        'category_id',
        'price'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Event::class;
    }
}
