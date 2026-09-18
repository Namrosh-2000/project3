<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string|null $phone
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $role
 * @property int $status
 * @property string|null $business_interests
 * @property string $experience_level
 * @property string|null $preferred_amenities
 * @property string|null $preferred_location
 * @property int|null $capital_budget
 * @property int $created_at
 * @property int $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const ROLE_SEEKER = 'seeker';
    const ROLE_OWNER = 'owner';
    const ROLE_AGENT = 'agent';
    const ROLE_ADMIN = 'admin';

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['username', 'email', 'role'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['business_interests'], 'string'],
            [['username'], 'string', 'max' => 50],
            [['email', 'password_hash', 'password_reset_token', 'preferred_amenities'], 'string', 'max' => 255],
            [['phone', 'experience_level'], 'string', 'max' => 20],
            [['preferred_location'], 'string', 'max' => 150],
            [['capital_budget'], 'integer'],
            [['auth_key'], 'string', 'max' => 32],
            [['role'], 'string', 'max' => 20],
            [['role'], 'in', 'range' => [self::ROLE_SEEKER, self::ROLE_OWNER, self::ROLE_AGENT, self::ROLE_ADMIN]],
            [['experience_level'], 'in', 'range' => ['beginner', 'under_1_year', '1_3_years', 'over_3_years']],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['role'], 'default', 'value' => self::ROLE_SEEKER],
            [['experience_level'], 'default', 'value' => 'beginner'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['password_reset_token'], 'unique'],
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getProperties()
    {
        return $this->hasMany(Property::class, ['owner_id' => 'id']);
    }

    public function getFavorites()
    {
        return $this->hasMany(Favorite::class, ['user_id' => 'id']);
    }

    /**
     * Single source of truth for "which dashboard does this user land on".
     * Used after login/signup and by the navbar's dashboard CTA, so the
     * role → destination mapping only lives in one place.
     */
    public function getDashboardRoute()
    {
        if ($this->role === self::ROLE_ADMIN) {
            return ['/admin/index'];
        }
        if (in_array($this->role, [self::ROLE_OWNER, self::ROLE_AGENT], true)) {
            return ['/dashboard/owner'];
        }
        return ['/dashboard/entrepreneur'];
    }
}
