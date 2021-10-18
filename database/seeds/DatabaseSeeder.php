<?php



use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberOfUsers = 10;

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        
        $this->call(RoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(EnterprisesTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(AttributesTableSeeder::class);
        $this->call(BrandsTableSeeder::class);
        $this->call(PriceTypesTableSeeder::class);
        $this->call(ItemTypesTableSeeder::class);

		//factory(User::class, $numberOfUsers)->create();
        $this->call(UnitOfMeasuresTableSeeder::class);
        $this->call(UnitOfMeasureConversionTableSeeder::class);

        $this->call(LocationsTableSeeder::class);
        $this->call(OauthClientsTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolePermissionsTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        
        $this->call(ItemsTableSeeder::class);
        $this->call(AppsTableSeeder::class);
        $this->call(MovementStatusesTableSeeder::class);
        $this->call(LocationTypesTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(ClientContactsTableSeeder::class);
        $this->call(SupplierContactsTableSeeder::class);
        $this->call(SaleBoxSeeder::class);
        $this->call(DiscountsTableSeeder::class);
        $this->call(ConfigsTableSeeder::class);
        $this->call(ConcilitationStatusesTableSeeder::class);
        $this->call(SiiDocumentTypesTableSeeder::class);
        $this->call(SiiTransferTypesTableSeeder::class);

        $this->call(ConfigsTableSeederAddTipoCaja::class);
        $this->call(ConfigsTableSeederAddApp::class);
        $this->call(ConfigsTableSeederAddTransfer::class);
        $this->call(ConfigsTableSeederAddVoucherMultiprint::class);
        $this->call(ConfigsTableSeederAddCreditGenerateTicket::class);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
