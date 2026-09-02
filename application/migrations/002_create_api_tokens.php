<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_api_tokens extends CI_Migration {

    public function up()
    {
        // Tabel api_tokens
        $this->dbforge->drop_table('api_tokens', TRUE);
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'nama_aplikasi' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'api_url' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'bearer_token' => array(
                'type' => 'TEXT',
            ),
            'is_active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 1,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('api_tokens');

        // Tabel api_logs
        $this->dbforge->drop_table('api_logs', TRUE);
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'token_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
            ),
            'endpoint' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'method' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'default' => 'GET',
            ),
            'response_code' => array(
                'type' => 'INT',
                'constraint' => '5',
                'null' => TRUE,
            ),
            'response_message' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'total_data' => array(
                'type' => 'INT',
                'constraint' => '11',
                'default' => 0,
            ),
            'status_sync' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => 'success',
            ),
            'error_message' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => TRUE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('token_id');
        $this->dbforge->create_table('api_logs');
    }

    public function down()
    {
        $this->dbforge->drop_table('api_logs', TRUE);
        $this->dbforge->drop_table('api_tokens', TRUE);
    }
}
