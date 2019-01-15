<?php


use Phinx\Migration\AbstractMigration;

class Users extends AbstractMigration
{
    /**
     * Migrate Up.
     */

    protected $tableUsers = 'users';

    public function up()
    {
        $users = $this->table($this->tableUsers);
        $users
            ->addColumn('name', 'string', ['null' => false])
            ->addColumn('email', 'string', ['null' => false])
            ->addColumn('password', 'string', ['null' => false])
            ->addColumn('created_at', 'timestamp')
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->save();
        $users->insert([
            'name' => 'Alex Dobrynin',
            'email' => 'ag.dobrynin@gmail.com',
            'password' => '123456'
        ]);
        $users->saveData();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table($this->tableUsers)->drop()->save();
    }
}
