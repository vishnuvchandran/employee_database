<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Employee_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'emp_table';
		$this->tbl_upload_file = 'upload_files';
    }
	
	//get all employee data
	public function getAllEmployees() {
		$this->db->select('*');
		$this->db->order_by('id', 'desc');
		$this->db->where('status', 1);
        $query = $this->db->get($this->table);
		return $query->result();
    }
	
	//Insert file name and column names
	public function insertFileData($data) {
        $this->db->insert($this->tbl_upload_file, $data);
        return $this->db->insert_id();
    }
	
	//retrieve uploaded details
	public function getUploadFile($uploadId) {
        $this->db->select('*');
        $this->db->where('upload_id', $uploadId);
        $this->db->where('status', 1);
        $query = $this->db->get($this->tbl_upload_file);
        return $query->row();
    }
	
	//update file info
	public function updateFileData($data, $uploadId) {
        $this->db->where('upload_id', $uploadId);
        $this->db->update($this->tbl_upload_file, $data);
        return $uploadId;
    }
	
	//check employee already exist
	public function checkEmployeeCode($empCode) {
        $this->db->select('*');
        $this->db->where('emp_code', $empCode);
		$this->db->where('status', 1);
        $query = $this->db->get($this->table);
        return $query->row();
    }
	
	//insert csv data
	public function insertEmployeeData($data) {
        return $this->db->insert_batch($this->table, $data);
    }
	
}	