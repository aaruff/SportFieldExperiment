<?php namespace SportExperiment\Model\Eloquent;

class WillingnessPayTreatment extends BaseEloquent
{
    public static $TABLE_KEY = 'willingness_to_pay_treatments';

    public static $ID_KEY = 'id';
    public static $SESSION_ID_KEY = 'session_id';
    public static $ENDOWMENT_KEY = 'endowment';

    private static $TASK_ID = 2;

    public $timestamps = false;

    protected $table;
    protected $fillable;
    protected $rules;

    /**
     * @param array $attributes
     */
    public function __construct($attributes = []){
        $this->table = self::$TABLE_KEY;
        $this->fillable = [self::$SESSION_ID_KEY, self::$ENDOWMENT_KEY];
        $this->rules = [self::$ENDOWMENT_KEY=>'required|numeric|min:1|max:1000000'];

        parent::__construct($attributes);
    }

    /* ---------------------------------------------------------------------
     * Model Relationships
     * ---------------------------------------------------------------------*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(Session::getNamespace(), self::$SESSION_ID_KEY);
    }

    /* ---------------------------------------------------------------------
     * Model Routines
     * ---------------------------------------------------------------------*/

    /**
     * @param Subject $subject
     * @return WillingnessPayEntry
     */
    public function calculatePayoff(Subject $subject)
    {
        $entry = $subject->getRandomWillingnessPayEntry();
        $endowment = $subject->getWillingnessPayTreatment()->getEndowment();
        $randomGoodPrice = lcg_value()*$endowment; // Generate a random number between 0 and the endowment

        // Subject Won Item
        if ($randomGoodPrice <= $entry->getWillingnessPay()) {
            $entry->setPayoff($endowment - $randomGoodPrice);
            $entry->setItemPurchased(true);
            return $entry;
        }

        // Subject Didn't Win Item
        $entry->setPayoff($endowment);
        $entry->setItemPurchased(false);
        return $entry;
    }

    /* ---------------------------------------------------------------------
     * Getters and Setters
     * ---------------------------------------------------------------------*/

    /**
     * Returns the Task ID
     * @return int
     */
    public static function getTaskId()
    {
        return self::$TASK_ID;
    }

    /**
     * @return mixed
     */
    public function getEndowment()
    {
        return $this->getAttribute(self::$ENDOWMENT_KEY);
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->getAttribute(self::$SESSION_ID_KEY);
    }

}