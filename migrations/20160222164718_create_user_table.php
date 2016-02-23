<?php

use Phinx\Migration\AbstractMigration;

class CreateUserTable extends AbstractMigration
{
  /**
   * executed each time we run a migration
   */
  public function up()
  {
    $users = $this->table('users');
    $users->addColumn('firstname', 'string')
          ->addColumn('lastname', 'string')
          ->addColumn('username', 'string')
          ->addIndex(['username'], ['unique' => true])
          ->addColumn('email', 'string')
          ->addIndex(['email'], ['unique' => true])
          ->addColumn('password', 'string')
          ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
          ->addColumn('updated_at', 'datetime', ['null' => true])
          ->save();
  }
  /**
   * executed each time we reverse a migration
   */
  public function down()
  {
    $this->dropTable('users');
  }
}
