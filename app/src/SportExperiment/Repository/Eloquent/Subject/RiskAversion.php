<?php namespace SportExperiment\Repository\Eloquent\Subject;

use SportExperiment\Repository\Eloquent\BaseEloquent;
use SportExperiment\Repository\Eloquent\Subject;

class RiskAversion extends BaseEloquent
{
    public static $TABLE_KEY = 'subject_risk_aversion';

    public static $ID_KEY = 'id';
    public static $SUBJECT_ID_KEY = 'subject_id';
    public static $INDIFFERENCE_PROBABILITY_KEY = 'indifference_probability';
    public static $PAYOFF_KEY = 'payoff';

    protected $rules;
    protected $table;
    protected $fillable;

    public function __construct($attributes = [])
    {
        $this->table = self::$TABLE_KEY;
        $this->rules = [
            self::$INDIFFERENCE_PROBABILITY_KEY=>'required|numeric|min:0|max:1',
        ];

        $this->fillable = [self::$INDIFFERENCE_PROBABILITY_KEY];

        parent::__construct($attributes);
    }

    /* ---------------------------------------------------------------------
     * Model Relationships
     * ---------------------------------------------------------------------*/

    public function subject()
    {
        return $this->belongsTo(Subject::getNamespace(), self::$SUBJECT_ID_KEY);
    }

    /* ---------------------------------------------------------------------
     * Getters and Setters
     * ---------------------------------------------------------------------*/

    public function setPayoff($payoff)
    {
        $this->setAttribute(self::$PAYOFF_KEY, $payoff);
    }

    public function getIndifferenceProbability()
    {
        return $this->getAttribute(self::$INDIFFERENCE_PROBABILITY_KEY);
    }

    public function getPayoff()
    {
        return $this->getAttribute(self::$PAYOFF_KEY);
    }

} 