<?php
namespace App\Traits;
use App\Helper;
use App\User;
use Illuminate\Validation\Validator;

trait ManagesDatabaseTransactions {

    /**
     * @throws \Exception
     */
    public function startDatabaseTransaction()
    {
        \DB::beginTransaction();
    }

    public function commitCurrentDatabaseTransaction()
    {
        // commit transactions if there's something to commit
        if(\DB::transactionLevel() != 0){
            \DB::commit();
        }
    }

    /**
     * @param int $upToLevel
     */
    public function finishDatabaseTransactions(int $upToLevel = 0)
    {
        // commit until no transactions remain
        while(\DB::transactionLevel() > $upToLevel){
            \DB::commit();
        }
    }

    /**
     * @param int $upToLevel
     * @throws \Exception
     */
    public function rollbackDatabaseTransactions(int $upToLevel = 0)
    {
        // rollback until no transactions remain
        while(\DB::transactionLevel() > $upToLevel){
            \DB::rollBack();
        }
    }
}
