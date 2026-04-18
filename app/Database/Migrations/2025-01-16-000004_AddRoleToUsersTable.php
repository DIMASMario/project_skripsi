<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleToUsersTable extends Migration
{
    public function up()
    {
        // Add role_id column to users table if it doesn't exist
        if (!$this->db->fieldExists('role_id', 'users')) {
            $this->forge->addColumn('users', [
                'role_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'role'
                ]
            ]);

            // Add foreign key constraint
            $this->forge->addForeignKey('role_id', 'user_roles', 'id', 'SET NULL', 'CASCADE', 'users');
        }

        // Update existing users to have role_id based on their role column
        $this->db->query("
            UPDATE users u 
            SET u.role_id = (
                SELECT ur.id 
                FROM user_roles ur 
                WHERE ur.role_name = u.role 
                LIMIT 1
            ) 
            WHERE u.role_id IS NULL
        ");
    }

    public function down()
    {
        if ($this->db->fieldExists('role_id', 'users')) {
            $this->forge->dropForeignKey('users', 'users_role_id_foreign');
            $this->forge->dropColumn('users', 'role_id');
        }
    }
}