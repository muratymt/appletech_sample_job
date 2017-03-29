<?php

namespace APPLE\migrations;

use APPLE\models\Clients;
use APPLE\models\Users;
use yii\db\Migration;
use yii\rbac\DbManager;
use yii\web\DbSession;

class M170329044447First extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTableUser($tableOptions);
        $this->createTableSessions($tableOptions);
        $this->createTableRbacRule($tableOptions);
        $this->createTableRbacItem($tableOptions);
        $this->createTableRbacItemChild($tableOptions);
        $this->createTableRbacAssignment($tableOptions);
        $this->createTableClients($tableOptions);

        $this->createRbac();
        $this->createUsers();
    }

    public function down()
    {
        /** @var DbManager $auth */
        $auth = \Yii::$app->authManager;
        $this->dropTable(Clients::tableName());
        $this->dropTable($auth->assignmentTable);
        $this->dropTable($auth->itemChildTable);
        $this->dropTable($auth->itemTable);
        $this->dropTable($auth->ruleTable);
        $this->dropTable('{{%sessions}}');
        $this->dropTable(Users::tableName());

        return true;
    }

    private function createRbac()
    {
        /** @var DbManager $auth */
        $auth = \Yii::$app->authManager;

        $roleAdmin = $auth->createRole('Super Admin');
        $permAdmin = $auth->createPermission('admin');

        $auth->add($roleAdmin);
        $auth->add($permAdmin);
        $auth->addChild($roleAdmin, $permAdmin);
    }

    private function createUsers()
    {
        $admin = new Users([
            'login' => 'admin',
            'authKey' => \Yii::$app->security->generateRandomString(32),
            'password' => 'admin',
        ]);
        $admin->save();

        $user = new Users([
            'login' => 'user',
            'authKey' => \Yii::$app->security->generateRandomString(32),
            'password' => 'user',
        ]);
        $user->save();

        /** @var DbManager $auth */
        $auth = \Yii::$app->authManager;
        $adminRole = $auth->getRole('Super Admin');
        $auth->assign($adminRole, $admin->userId);
    }

    private function createTableClients($tableOptions)
    {
        $columns = [
            'clientId' => $this->primaryKey(),
            'fio' => $this->string(128)->notNull()->unique(),
            'address' => $this->string(200),
            'email' => $this->string(128)->unique(),
        ];

        $this->createTable(Clients::tableName(), $columns, $tableOptions);
    }

    private function createTableUser($tableOptions)
    {
        $columns = [
            'userId' => $this->primaryKey(),
            'login' => $this->string(20)->notNull(),
            'authKey' => $this->string(256)->notNull(),
            'password' => $this->string(64)->notNull(),
        ];
        $this->createTable(Users::tableName(), $columns, $tableOptions);
    }

    private function createTableRbacAssignment($tableOptions)
    {
        /** @var DbManager $auth */
        $auth = \Yii::$app->authManager;

        $columns = [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
        ];
        $this->createTable($auth->assignmentTable, $columns, $tableOptions);
        $this->addPrimaryKey('rbacAssignment_pk', $auth->assignmentTable, ['item_name', 'user_id']);

        $this->addForeignKey('assignment_item_fk', $auth->assignmentTable, 'item_name', $auth->itemTable, 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('assignment_user_fk', $auth->assignmentTable, 'user_id', Users::tableName(), 'userId', 'CASCADE', null);
    }

    private function createTableRbacRule($tableOptions)
    {
        /** @var DbManager $auth */
        $auth = \Yii::$app->authManager;

        $columns = [
            'name' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),

        ];

        $this->createTable($auth->ruleTable, $columns, $tableOptions);
        $this->addPrimaryKey('rbacRule_pk', $auth->ruleTable, 'name');
    }

    private function createTableRbacItemChild($tableOptions)
    {
        /** @var DbManager $auth */
        $auth = \Yii::$app->authManager;

        $columns = [
            'parent' => $this->string(64),
            'child' => $this->string(64),
        ];

        $this->createTable($auth->itemChildTable, $columns, $tableOptions);
        $this->addPrimaryKey('rbacItemChild_pk', $auth->itemChildTable, ['parent', 'child']);
        $this->addForeignKey('item_parent_fk', $auth->itemChildTable, 'parent', $auth->itemTable, 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('item_child_fk', $auth->itemChildTable, 'child', $auth->itemTable, 'name', 'CASCADE', 'CASCADE');
    }

    private function createTableRbacItem($tableOptions)
    {
        /** @var DbManager $auth */
        $auth = \Yii::$app->authManager;

        $columns = [
            'name' => $this->string(64)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),

        ];

        $this->createTable($auth->itemTable, $columns, $tableOptions);
        $this->addPrimaryKey('rbacItem_pk', $auth->itemTable, 'name');
        $this->addForeignKey('rule_fk', $auth->itemTable, 'rule_name', '{{%rbacRule}}', 'name', 'SET NULL', 'CASCADE');
        $this->createIndex('type_idx', $auth->itemTable, 'type');
    }

    private function createTableSessions($tableOptions)
    {
        $columns = [
            'id' => $this->string(128)->notNull(),
            'expire' => $this->integer()->notNull(),
            'data' => 'LONGBLOB',
        ];

        if ($this->db->driverName === 'pgsql') {
            $columns['data'] = 'BYTEA';
        }

        $this->createTable('{{%sessions}}', $columns, $tableOptions);
        $this->addPrimaryKey('session_pk', '{{%sessions}}', 'id');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
