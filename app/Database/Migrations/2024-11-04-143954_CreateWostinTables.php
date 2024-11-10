<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWostinTables extends Migration
{
    public function up()
    {
        // User Group Table
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'            => ['type' => 'ENUM', 'constraint' => ['admin', 'customer']],
            'description'     => ['type' => 'TEXT', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);        
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('user_groups');

        // Users Table
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'email'           => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'password'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'phone_number'    => ['type' => 'VARCHAR', 'constraint' => 15],
            'location'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'email_verified'  => ['type' => 'BOOLEAN', 'default' => false],
            'phone_verified'  => ['type' => 'BOOLEAN', 'default' => false],
            'user_group_id'    => ['type' => 'INT', 'unsigned' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_group_id', 'user_groups', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('users');


        // Verification Codes Table
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INT', 'unsigned' => true],
            'code'       => ['type' => 'VARCHAR', 'constraint' => 10],
            'expires_at' => ['type' => 'DATETIME'],
            'type'       => ['type' => 'ENUM', 'constraint' => ['email', 'phone']],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('verification_codes');

        // Bin Sizes Table
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'size'          => ['type' => 'VARCHAR', 'constraint' => 50],    // Size of the bin (e.g., small, medium, large)
            'size_multiplier'          => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'description'   => ['type' => 'TEXT', 'null' => true],           // Description of the bin size
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('bin_sizes');

        // Waste Types Table
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'type'          => ['type' => 'VARCHAR', 'constraint' => 100],  // Type of waste (e.g., organic, recyclable)
            'description'   => ['type' => 'TEXT', 'null' => true],          // Description of the waste type
            'cost'     => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('waste_types');

        // Pending Schedules Table
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'INT', 'unsigned' => true],
            'collection_date' => ['type' => 'DATE'],
            'total_cost'     => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'bin_quantity'=> ['type' => 'INT',],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pending_schedules');

        // Schedules Table
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'INT', 'unsigned' => true],
            'collection_date' => ['type' => 'DATE'],
            'status'         => ['type' => 'ENUM', 'constraint' => ['pending', 'completed', 'cancelled', 'awaiting_approval', 'rejected'], 'default' => 'pending'],
            'bin_quantity'=> ['type' => 'INT',],
            'pending_approval' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'is_approved' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'total_cost'     => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('schedules');

        // Pending ScheduleBins Table
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'pending_schedule_id'  => ['type' => 'INT', 'unsigned' => true],
            'bin_size_id'  => ['type' => 'INT', 'unsigned' => true],        // Foreign key to bin_sizes
            'waste_type_id'=> ['type' => 'INT', 'unsigned' => true],        // Foreign key to waste_types
            'quantity'=> ['type' => 'INT',],
            'cost'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('pending_schedule_id', 'pending_schedules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('bin_size_id', 'bin_sizes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('waste_type_id', 'waste_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pending_schedule_bins');

        // ScheduleBins Table
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'schedule_id'  => ['type' => 'INT', 'unsigned' => true],
            'bin_size_id'  => ['type' => 'INT', 'unsigned' => true],        // Foreign key to bin_sizes
            'waste_type_id'=> ['type' => 'INT', 'unsigned' => true],        // Foreign key to waste_types
            'quantity'=> ['type' => 'INT',],
            'cost'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('schedule_id', 'schedules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('bin_size_id', 'bin_sizes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('waste_type_id', 'waste_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('schedule_bins');

        // Payments Table
        // $this->forge->addField([
        //     'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
        //     'schedule_id'   => ['type' => 'INT', 'unsigned' => true],
        //     'user_id'   => ['type' => 'INT', 'unsigned' => true],
        //     'amount'        => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        //     'status'        => ['type' => 'ENUM', 'constraint' => ['pending', 'completed', 'failed'], 'default' => 'pending'],
        //     'payment_date'  => ['type' => 'DATETIME', 'null' => true],
        // ]);
        // $this->forge->addPrimaryKey('id');
        // $this->forge->addForeignKey('schedule_id', 'schedules', 'id', 'CASCADE', 'CASCADE');
        // $this->forge->createTable('payments');

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true); // Primary Key
        $this->forge->addKey('date'); // Index on 'date' for fast lookups

        $this->forge->createTable('unavailable_dates');
    }

    public function down()
    {
        // Drop tables in reverse order to avoid foreign key issues
        $this->forge->dropTable('payments', true);
        $this->forge->dropTable('schedule_bins', true);
        $this->forge->dropTable('schedules', true);
        $this->forge->dropTable('waste_types', true);
        $this->forge->dropTable('bin_sizes', true);
        $this->forge->dropTable('verification_codes', true);
        $this->forge->dropTable('user_groups', true);
        $this->forge->dropTable('users', true);
        $this->forge->dropTable('unavailable_dates');
    }
}
