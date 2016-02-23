<?php

use Phinx\Migration\AbstractMigration;

class CreateAuctionTable extends AbstractMigration
{
  /**
   * executed each time we run a migration
   */
  public function up()
  {
    $auctions = $this->table('auctions');
    $auctions->addColumn('title', 'string')
        ->addColumn('description', 'text')
        ->addColumn('starttime', 'datetime', ['null' => true])
        ->addColumn('endtime', 'datetime', ['null' => true])
        ->addIndex(['endtime'])
        ->addColumn('starting_price', 'decimal', ['precision' => 10,
                                                  'scale' => 3])
        ->addColumn('current_price', 'decimal', ['precision' => 10,
                                                 'scale' => 3])
        ->addColumn('seller_id', 'integer', ['signed' => false])
        ->addIndex(['seller_id'])
        ->addColumn('buyer_id', 'integer', ['signed' => false])
        ->addIndex(['buyer_id'])
        ->addColumn('status', 'string', ['limit' => 10])
        ->addIndex(['status'])
        ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('updated_at', 'datetime', ['null' => true])
        ->save();
  }
  /**
   * executed each time we reverse a migration
   */
  public function down()
  {
    $this->dropTable('auctions');
  }
}
