<?php

namespace tframe\common\models;

use tframe\core\Application;
use tframe\core\auth\AuthAssignment;
use tframe\core\auth\AuthItem;
use tframe\core\auth\Roles;
use tframe\core\auth\UserRoles;
use tframe\core\database\MagicRecord;

/**
 * @property integer $id
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property string $password
 * @property boolean $email_confirmed
 * @property string $token
 * @property string $created_at
 * @property string $updated_at
 */

class User extends MagicRecord {
    public static function tableName(): string {
        return "users";
    }
    public static function primaryKey(): string|array { return 'id'; }

    public function attributes(): array {
        return [
            'email',
            'firstName',
            'lastName',
            'password',
            'token',
            'email_confirmed',
        ];
    }

    public function labels(): array {
        return [
            'email' => Application::t('attributes','Email address'),
            'firstName' => Application::t('attributes','Given name'),
            'lastName' => Application::t('attributes','Family name'),
            'password' => Application::t('attributes','Password'),
            'token' => Application::t('attributes','Token'),
            'email_confirmed' => Application::t('attributes','Email confirmed')
        ];
    }

    public function rules(): array {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class], 'attribute'],
            'firstName' => [self::RULE_REQUIRED],
            'lastName' => [self::RULE_REQUIRED],
            'password' => [self::RULE_REQUIRED, self::RULE_PASSWORD],
            'token' => [self::RULE_REQUIRED, [self::RULE_UNIQUE, 'class' => self::class, 'attribute' => 'token']],
        ];
    }

    public function getFullName(): string {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getPicture(): string {
        return file_exists('./assets/images/profile-pictures/' . $this->{$this->primaryKey()} . '.png') ? '/assets/images/profile-pictures/' . $this->{$this->primaryKey()} . '.png' : '/assets/images/user-dummy.png';
    }

    public function getRoles(): array|false {
        return UserRoles::findMany(['userId' => $this->id]);
    }

    private static function isItemMatches(false|array $auths, string $route): bool {
        $can = false;
        foreach ($auths as $auth) {
            /** @var $item AuthItem */
            $item = AuthItem::findOne([AuthItem::primaryKey() => $auth->item]);
            $routeRegex = "@^" . preg_replace_callback('/\{\w+(:([^}]+))?}/', fn($m) => isset($m[2]) ? "({$m[2]})" : '(\w+)', trim(substr($item->item, 1), '/')) . "$@";
            if (preg_match($routeRegex, trim(substr($route, 1), '/'))) {
                $can = true;
                break;
            }
        }
        return $can;
    }

    public static function canRoute(User|null $user, string $route): bool {
        $can = false;
        if(is_null($user)) {
            $auths = AuthAssignment::findMany(['role' => 2]);
            /** @var $auth AuthAssignment */
            $can = self::isItemMatches($auths, $route);
        } else {
            $roles = UserRoles::findMany(['userId' => $user->id]);
            /** @var $assignment UserRoles */
            foreach ($roles as $assignment) {
                /** @var $role Roles */
                $role = Roles::findOne([Roles::primaryKey() => $assignment->roleId]);
                $auths = AuthAssignment::findMany(['role' => $role->id]);
                /** @var $auth AuthAssignment */
                $can = self::isItemMatches($auths, $route);
            }
        }
        return $can;
    }
}