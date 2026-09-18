<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Profile update form (name, email, phone).
 */
class ProfileForm extends Model
{
    public $email;
    public $phone;
    public $business_interests = [];
    public $experience_level = 'beginner';
    public $preferred_amenities = [];
    public $preferred_location;
    public $capital_budget;

    private $_user;

    public function __construct($user = null, $config = [])
    {
        if ($user !== null) {
            $this->_user = $user;
            $this->email = $user->email;
            $this->phone = $user->phone;
            // Zamani thamani zilikuwa beginner/intermediate/expert; zibadilishe ziendane na
            // machaguo mapya manne ya Hatua ya 2 ya wizard.
            $legacyMap = ['intermediate' => '1_3_years', 'expert' => 'over_3_years'];
            $level = $user->experience_level ?: 'beginner';
            $this->experience_level = $legacyMap[$level] ?? $level;
            $this->business_interests = !empty($user->business_interests) ? explode(',', $user->business_interests) : [];
            $this->preferred_amenities = !empty($user->preferred_amenities) ? explode(',', $user->preferred_amenities) : [];
            $this->preferred_location = $user->preferred_location;
            $this->capital_budget = $user->capital_budget;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email',
                'filter' => ['<>', 'id', $this->_user ? $this->_user->id : 0],
                'message' => 'This email is already taken.'],
            [['phone', 'experience_level'], 'string', 'max' => 20],
            [['preferred_location'], 'string', 'max' => 150],
            [['capital_budget'], 'integer', 'min' => 0],
            [['business_interests', 'preferred_amenities'], 'safe'],
        ];
    }

    public function save()
    {
        if (!$this->validate() || $this->_user === null) {
            return false;
        }
        $this->_user->email = $this->email;
        $this->_user->phone = $this->phone;
        $this->_user->experience_level = $this->experience_level;
        $this->_user->business_interests = is_array($this->business_interests) ? implode(',', $this->business_interests) : (string)$this->business_interests;
        $this->_user->preferred_amenities = is_array($this->preferred_amenities) ? implode(',', $this->preferred_amenities) : (string)$this->preferred_amenities;
        $this->_user->preferred_location = $this->preferred_location;
        $this->_user->capital_budget = $this->capital_budget !== '' ? $this->capital_budget : null;

        return $this->_user->save(false);
    }
}