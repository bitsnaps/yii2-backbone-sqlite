<?php

namespace app\models;

/**
* This is the model class for table "user".
*
* @property int $id
* @property string $username
* @property string $password
* @property string $email
* @property int $status
* @property string $auth_key
* @property string $access_token
* @property string $password_reset_token
*/
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;


    public static function tableName(){
      return '{{%user}}';
    }

    public function rules(){
      return [
        [['username', 'password', 'auth_key', 'access_token'], 'required'],
        [['status'], 'integer'],
        [['username'], 'string', 'max' => 50],
        [['password', 'auth_key', 'access_token'], 'string', 'max' => 250],
        [['email'], 'string', 'max' => 255],
        [['password_reset_token'], 'string', 'max' => 512],
      ];
    }

    public function attributeLabels(){
        return [
          'id' => 'ID',
          'username' => 'Username',
          'password' => 'Password',
          'email' => 'Email',
          'status' => 'Status',
          'auth_key' => 'Auth Key',
          'access_token' => 'Access Token',
          'password_reset_token' => 'Password Reset Token',
      ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }

    public function generateAuthKey()
    {
      $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
    * Generate Access Token
    */
    public function generateAccessToken()
    {
      $this->access_token = \Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = static::findOne(['username' => $username]);
        if ($user){
            return $user;
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Set password
     *
     * @param string $password password to changed
     */
    public function setPassword($password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
}
