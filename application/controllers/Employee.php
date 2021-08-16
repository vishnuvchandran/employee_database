<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {
	
	public function __construct() { 
		parent::__construct(); 
		
		$this->load->model('employee_model');
		$this->created_at = date("Y-m-d G:i:s");
    }

	//Employee list view
	public function index() {
		$data['employees'] = $this->employee_model->getAllEmployees();
		
		$this->load->view('employee/list_view', $data);
	}
	
	//Upload csv file
	public function uploadEmployeeData() {
		$config['upload_path']="./uploads";
        $config['allowed_types']='csv';
        $this->load->library('upload',$config);
        if($this->upload->do_upload("emp_data")){
			$data = array('upload_data' => $this->upload->data());
			$uploadData = array(
								'file_name' => $data['upload_data']['file_name'],
								'column_1' => $this->input->post('column1'),
								'column_2' => $this->input->post('column2'),
								'column_3' => $this->input->post('column3'),
								'column_4' => $this->input->post('column4'),
								'column_5' => $this->input->post('column5'),
								'created_at' => $this->created_at
							);  
			
			$uploadFileId = $this->employee_model->insertFileData($uploadData);
			if (!empty($uploadFileId)) {
				redirect('employee/ProcessCsvData/' . $uploadFileId);
			}
        } else {
			$errors = $this->upload->display_errors();
			$result = array('status' => 0, "message"=>$errors);
		}
		echo json_encode($result);
	}
	
	//Process uploaded csv file
	public function ProcessCsvData($fileId = NULL) {
		$fileDetails = $this->employee_model->getUploadFile($fileId);
		$filePath = FCPATH . "uploads/$fileDetails->file_name";
		
		$errors = "";
		$fp = file($filePath);
		$numRows = count($fp);
		if ($numRows > 20) {
			$errors .= 'Limit exceeded, application allow up to 20 rows <br/>';
		}
		$file = fopen($filePath, "r");
		
		$nameIndex = "";
		$codeIndex = "";
		$dptIndex = "";
		$dobIndex = "";
		$joinIndex = "";
		$empCodeDuplicate = array();
		$empCodes = array();
		$emptyField = array();
		$invalidDob = array();
		$invalidJoin = array();
		$i = 0;
		while (!feof($file)) {
			$tempFileData = fgetcsv($file);
			if (empty($tempFileData)) {
				break;
			}
			if ($i == 0) {
				
				$numColumns = count($tempFileData);
				if ($numColumns < 5) {
					$errors .= 'Incorrect data structure, File should contain atleast 5 columns <br/>';
					break;
				}
				
				if (strtolower($tempFileData[0]) == $fileDetails->column_1 && strtolower($tempFileData[1]) == $fileDetails->column_2 && strtolower($tempFileData[2]) == $fileDetails->column_3 && strtolower($tempFileData[3]) == $fileDetails->column_4 && strtolower($tempFileData[4]) == $fileDetails->column_5) {
					
					foreach ($tempFileData as $key => $value) {
						$columName = strtolower($value);
						
						switch ($columName) {
							case "name":
								$nameIndex = $key;
								break;
							case "employee code":
								$codeIndex = $key;
								break;	
							case "department":
								$dptIndex = $key;
								break;
							case "date of birth":
								$dobIndex = $key;
								break;
							case "joining date":
								$joinIndex = $key;
						}
					}
					
					$i++;
					continue;
				} else {
					$data = array(
						'status' => 0
					);
					$this->employee_model->updateFileData($data, $fileId);
					$errors .= 'Data structure is incorrect <br/>' ;
					break;
				}
			}
			
			if (($i < $numRows) && ($tempFileData[0] == '' || $tempFileData[1] == '' || $tempFileData[2] == '' || $tempFileData[3] == '' || $tempFileData[4] == '')) {
				$emptyField[] = $i+1;
			}	
			
			if ($tempFileData[$codeIndex] != '') {
				$checkDuplicateEntry = $this->employee_model->checkEmployeeCode($tempFileData[$codeIndex]);
				if (!empty($checkDuplicateEntry)) {
					$empCodeDuplicate[] = $tempFileData[$codeIndex];
				} elseif (in_array($tempFileData[$codeIndex], $empCodes)) {
					$empCodeDuplicate[] = $tempFileData[$codeIndex];
				}
			}
			
			if (strtotime($tempFileData[$dobIndex]) === false) {
				$invalidDob[] = $i+1;
			} 
			if (strtotime($tempFileData[$joinIndex]) === false) {
				$invalidJoin[] = $i+1;
			}
			
			$empCodes[] = $tempFileData[$codeIndex];
			if (empty($emptyField) && empty($empCodeDuplicate) && empty($invalidDob) && empty($invalidJoin)) {
				$masterData[] = array(
					'emp_code' => $tempFileData[$codeIndex],
					'name' => $tempFileData[$nameIndex],
					'department' => $tempFileData[$dptIndex],
					'dob' =>  date('Y-m-d',strtotime($tempFileData[$dobIndex])),
					'joining_date' => date('Y-m-d',strtotime($tempFileData[$joinIndex])),
					'created_at' => $this->created_at
				);
			}
			$i++;
		}

		fclose($file);

		if (!empty($emptyField)) {
			$errors .= 'Unable to process the file due to empty fields on row: ' . implode(", ",$emptyField) . '<br/>';
		}
		if (!empty($empCodeDuplicate)) {
			$errors .= 'Unable to process the file due to duplicate Employee code: '.implode(", ", $empCodeDuplicate) . '<br/>';
		} 
		if (!empty($invalidDob)) {
			$errors .= 'Invalid data on date of birth field, row no: ' . implode(", ",$invalidDob) . '<br/>';
		}
		if (!empty($invalidJoin)) {
			$errors .= 'Invalid data on joining date field, row no: ' . implode(", ",$invalidJoin) . '<br/>';
		}		
			
		if (!empty($errors)) {
			$result = array('status' => 0, "message"=>$errors);
		} elseif (!empty($masterData)) {
			$this->employee_model->insertEmployeeData($masterData);	
			$result = array('status' => 1, "message"=>'Successfully uploaded employee data');
		}	
		echo json_encode($result);	
	}
}
