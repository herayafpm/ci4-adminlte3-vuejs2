<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'admin';
	protected $primaryKey           = 'admin_id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = ['admin_username', 'admin_nama', 'role_id', 'admin_aktif', 'admin_password'];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'admin_created_at';
	protected $updatedField         = 'admin_updated_at';
	protected $deletedField         = '';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = ['hashPassword', 'namaLower'];
	protected $afterInsert          = [];
	protected $beforeUpdate         = ['hashPassword', 'namaLower'];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];
	protected function hashPassword(array $data)
	{
		if (!isset($data['data']['admin_password'])) return $data;
		$data['data']['admin_password'] = password_hash($data['data']['admin_password'], PASSWORD_DEFAULT);
		return $data;
	}
	protected function namaLower(array $data)
	{
		if (!isset($data['data']['admin_nama'])) return $data;
		$data['data']['admin_nama'] = strtolower($data['data']['admin_nama']);
		return $data;
	}
	public function authenticate($username, $password)
	{
		$auth = $this->where($this->allowedFields[0], $username)->first();
		if ($auth) {
			if (password_verify($password, $auth[$this->allowedFields[sizeof($this->allowedFields) - 1]])) {
				return $this->getAdmin($auth[$this->primaryKey]);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function getAdmin($admin_id)
	{
		$builder = $this->db->table($this->table);
		$builder->select("{$this->table}.*");
		$builder->select("role.*");
		$builder->join('role', "role.role_id = {$this->table}.role_id", 'LEFT');
		$builder->where([$this->primaryKey => $admin_id]);
		$query = $builder->get()->getRowArray();
		$query['is_aktif'] = $this->isAktif($query['admin_aktif']);
		return $query;
	}

	public function isAktif($status)
	{
		return $status == 1;
	}

	public function filter($limit, $start, $orderBy, $ordered, $params = [])
	{
		$builder = $this->db->table($this->table);
		$builder->select("{$this->table}.*");
		$builder->select("role.*");
		$builder->join('role', "role.role_id = {$this->table}.role_id", 'LEFT');
		if (isset($params['where'])) {
			$builder->where($params['where']);
		}
		if (isset($params['like'])) {
			foreach ($params['like'] as $key => $value) {
				$builder->like($key, $value);
			}
		}
		$builder->orderBy($orderBy, $ordered);
		if ($limit > 0) {
			$builder->limit($limit, $start);
		}
		$datas = $builder->get()->getResultArray();
		return $datas;
	}
	public function countAll($params = [])
	{
		$builder = $this->db->table($this->table);
		$builder->select("{$this->table}.*");
		$builder->select("role.*");
		$builder->join('role', "role.role_id = {$this->table}.role_id", 'LEFT');
		if (isset($params['where'])) {
			$builder->where($params['where']);
		}
		if (isset($params['like'])) {
			foreach ($params['like'] as $key => $value) {
				$builder->like($key, $value);
			}
		}
		return $builder->countAllResults();
	}
}
