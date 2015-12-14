<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EntrustSetupTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Creates the assigned_roles (Many-to-Many relation) table
        Schema::create('assigned_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->foreign('staff_id')->references('id')->on('staffs')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles');
        });

        // Creates the permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name');
            $table->timestamps();
        });

        // Creates the permission_role (Many-to-Many relation) table
        Schema::create('permission_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->foreign('role_id')->references('id')->on('roles');
        });

        $this->setupBaseRolsPermission();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assigned_roles', function (Blueprint $table) {
            $table->dropForeign('assigned_roles_staff_id_foreign');
            $table->dropForeign('assigned_roles_role_id_foreign');
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->dropForeign('permission_role_permission_id_foreign');
            $table->dropForeign('permission_role_role_id_foreign');
        });

        Schema::drop('assigned_roles');
        Schema::drop('permission_role');
        Schema::drop('roles');
        Schema::drop('permissions');
    }

    public function setupBaseRolsPermission()
    {
        // Create Roles
        $admin = new Role();
        $admin->name = 'Admin';
        $admin->save();

        $service = new Role;
        $service->name = 'Service';
        $service->save();

        // Create User
        $staff = Staff::create([
                'name' => 'admin',
                'password' => Hash::make('administrator'),
                'real_name' => 'Administrator'
            ]);

        // Attach Roles to user
        $staff->roles()->attach($admin->id);

        // Create Permissions
        $manageStaffs = new Permission;
        $manageStaffs->name = 'manage_staffs';
        $manageStaffs->display_name = 'Manage Staffs';
        $manageStaffs->save();

        $manageVersions = new Permission;
        $manageVersions->name = 'manage_versions';
        $manageVersions->display_name = 'Manage Versions';
        $manageVersions->save();

        $manageServices = new Permission;
        $manageServices->name = 'manage_services';
        $manageServices->display_name = 'Manage Services';
        $manageServices->save();

        // Assign Permission to Role
        $admin->perms()->sync([$manageStaffs->id, $manageVersions->id, $manageServices->id]);
        $service->perms()->sync([$manageServices->id]);
    }
}
