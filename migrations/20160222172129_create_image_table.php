<?php

use Phinx\Migration\AbstractMigration;

class CreateImageTable extends AbstractMigration
{
  /**
   * executed each time we run a migration
   */
  public function up()
  {
    $images = $this->table('images');
    $images->addColumn('auction_id', 'integer', ['signed' => false])
        ->addIndex(['auction_id'])
        ->addColumn('path', 'string')
        ->addColumn('title', 'string', ['limit' => 25])
        ->addColumn('sequence', 'integer', ['signed' => false,
                                            'limit' => 2])
        ->addIndex(['sequence'])
        ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('updated_at', 'datetime', ['null' => true])
        ->save();
    #$images->addForeignKey('auction_id', 'auctions', 'id', ['delete' => 'CASCADE',
    #                                                        'update' => 'CASCADE'])
    #    ->save();
  }
  /**
   * executed each time we reverse a migration
   */
  public function down()
  {
    $this->dropTable('images');
  }
}
