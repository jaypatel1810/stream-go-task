<?php

namespace App\Helpers;

class Reports
{

    /**
     * [$dataCollection wil contains parsed data collection]
     * @var array
     */
	private $dataCollection = [];

	/**
	 * [$reportParam will contians report parameter passed through session]
	 * @var array
	 */
	private $reportParam = [];

	/**
	 * [$reportType is to define which kind of report need to generate / DATA / EXPORT]
	 * @var string
	 */
	private $reportType = 'DATA';

	/**
	 * [$reportExportType is to define export type PDF / Excel / JSON / XML]
	 * @var string
	 */
	private $reportExportType = 'PDF'

	/**
	 * [$metaQueryParam will contains common query conditions]
	 * @var [type]
	 */
	private $metaQueryParam = [ // For displaying filters description on header
        'fromDate' => date("Y-m-d"),
        'toDate' => date("Y-m-d")
    ];

	/**
	 * [__construct will set report basic param from session]
	 */
	public function __construct(){
		$this->reportType = Session::get('reportType');
		$this->reportParam = Session::get('reportParam');
		$this->reportExportType = Session::get('reportExportType');
	}

	/**
	 * [build description]
	 * @return [type] [description]
	 */
	public function build(){
		$data = [];
		if($this->reportParam['data_source'] == 'ELOQUENT'){
			$data = $this->fetchDataEloquent();
		}else if($this->reportParam['data_source'] == 'DB'){
			$data = $this->fetchDataEloquent();
		}

		$this->parseData($data);		

	}


	/**
	 * [this function will return report data]
	 * @return [array] [return value will contains collection of data]
	 */
	public function get(){
		return $this->dataCollection;
	}


	/**
	 * [export description]
	 * @return [file name] [this function will generate exported files]
	 */
	public function export(){
		$reportData = $this->dataCollection;
		// USE report Data and export relavent report

		if($this->reportExportType == 'PDF'){
			// TODO:: Call Generate and Export PDF Function
		}else if($this->reportExportType == 'EXCEL'){
			// TODO:: Call Generate and Export EXCEL Function
		}else if($this->reportExportType == 'JSON'){
			// TODO:: Call Generate and Export JSON Function
		}else if($this->reportExportType == 'XML'){
			// TODO:: Call Generate and Export XML Function
		}

		return $filename; // return file name or file URL
	}


	/**
	 * [this function will parse collected data in require formats]
	 * @return [type] [description]
	 */
	private function parseData($data){
		$reportData['columns'] = $this->reportParam['fields']
		$reportData['data'] = $data;
		$this->dataCollection = $reportData;		
	}


	private function fetchDataEloquent(){
		$queryBuilder = Model::select($this->reportParam['fields']);
		if($this->reportParam['date_range']){
			$queryBuilder->whereBetween($this->reportParam['date_range']['field'], 
				[$this->reportParam['date_range']['date_from'], 
				$this->reportParam['date_range']['date_to']]);
		}

		// can add other conditional queries here...

		if($this->reportParam['sort']){
			$resultData = $queryBuilder->orderBy($this->reportParam['sort']['field'],
				$this->reportParam['sort']['sort_type']);
		}else{
			$resultData = $queryBuilder->get();
		}

		return $resultData;
	}

	private function fetchDataDb(){
		$queryBuilder = DB::table($this->reportParam['table'])->select($this->reportParam['fields']);
		
		if($this->reportParam['date_range']){
			$queryBuilder->whereBetween($this->reportParam['date_range']['field'], 
				[$this->reportParam['date_range']['date_from'], 
				$this->reportParam['date_range']['date_to']]);
		}

		// can add othere conditional queries here...
		
		if($this->reportParam['sort']){
			$resultData = $queryBuilder->orderBy($this->reportParam['sort']['field'],
				$this->reportParam['sort']['sort_type']);
		}else{
			$resultData = $queryBuilder->get();
		}

		return $resultData;
	}	


}