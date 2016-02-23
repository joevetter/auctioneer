<?php

use Phinx\Migration\AbstractMigration;

class CreateBidTable extends AbstractMigration
{
  /**
   * executed each time we run a migration
   */
  public function up()
  {
    $bids = $this->table('bids');
    $bids->addColumn('auction_id', 'integer', ['signed' => false])
        ->addIndex(['auction_id'])
        ->addColumn('bidder_id', 'integer', ['signed' => false])
        ->addIndex(['bidder_id'])
        ->addColumn('amount', 'decimal')
        ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('updated_at', 'datetime', ['null' => true])
        ->save();
    #$bids->addForeignKey('auction_id', 'auctions', 'id')
    #    ->save();
  }
  /**
   * executed each time we reverse a migration
   */
  public function down()
  {
    $this->dropTable('bids');
  }
}
