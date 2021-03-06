<?php

namespace ProcessMaker\Model;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Support\Facades\Validator;
use ProcessMaker\Exception\ValidationException;
use ProcessMaker\Model\Traits\Uuid;

/**
 * Categories are used to classify and group similar processes within different
 * categories. Only one category may be assigned by process.
 *
 * @property int $CATEGORY_ID
 * @property string $CATEGORY_UID
 * @property string $CATEGORY_NAME
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ProcessCategory extends Model
{

    use ValidatingTrait;
    use Uuid;

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    /**
     * Validation rules.
     *
     * @var array $rules
     */
    public $rules = [
        'name' => 'required|string|max:100|unique:process_categories,name',
        'status' => 'required|string|in:ACTIVE,INACTIVE',
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array $fillable
     */
    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uid';
    }

    /**
     * Processes of the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    /**
     * Check that the category has no processes before deleting 
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete()
    {
        $validator = Validator::make([
            'processCategory' => $this,
        ], [
            'processCategory' => 'process_category_manager.category_does_not_have_processes',
        ]);

        $validator->addExtension(
            'process_category_manager.category_does_not_have_processes',
            function ($attribute, $processCategory, $parameters, $validator) {
                return $processCategory->processes()->count() === 0;
            }
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        parent::delete();
    }
}
