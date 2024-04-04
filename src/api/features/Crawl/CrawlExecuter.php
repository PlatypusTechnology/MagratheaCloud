<?php

namespace MagratheaCloud\Crawl;

use Exception;
use Magrathea2\Exceptions\MagratheaApiException;
use Magrathea2\MagratheaApi;
use Magrathea2\MagratheaHelper;
use MagratheaCloud\Explorer;
use MagratheaCloud\File\File;
use MagratheaCloud\Folder\Folder;

use function Magrathea2\now;
use function Magrathea2\p_r;

class CrawlExecuter {

	private Crawl $crawl;
	private CrawlControl $control;
	private Explorer $explorer;
	private int $keyId;
	private array $log = [];
	private string $startTime;
	private string $endTime = "";

	public function __construct(Crawl $c = null) {
		$this->SetCrawl($c);
		$this->control = new CrawlControl();
		$this->explorer = new Explorer();
	}
	public function SetCrawl($c): CrawlExecuter {
		$this->crawl = $c;
		$this->keyId = $this->crawl->apikey;
		return $this;
	}

	public function Run() {
		if(!$this->Start()) return false;
		try {
			$this->explorer->SetCrawlFolder($this->crawl->GetFolderKey());
			$this->CrawlFolder("/");
			$this->End();
		} catch(\Exception $ex) {
			$this->LogError($ex->getMessage());
			return $this->crawl;
		}
		return $this->GenerateReport();
	}

	public function CrawlFolder($folder) {
		$folder = MagratheaHelper::EnsureTrailingSlash($folder);
		$data = $this->explorer->GetFolderData($folder);
		foreach($data as $item) {
			if($item["type"] == "folder") {
				$this->CreateFolder($folder, $item["item"]);
				$f = $folder.$item["item"];
				$this->CrawlFolder($f);
			} else {
				$this->CreateFile($folder, $item["item"]);
			}
		}
	}

	public function CreateFolder(string $location, string $name) {
		$this->Log("Folder [".$name."] found at [".$location."]");
		if($this->control->CheckIfFolderExists($this->keyId, $location, $name)) return true;
		$folder = new Folder();
		$folder->name = $name;
		$folder->location = $location;
		$folder->apikey_id = $this->keyId;
		$rs = $folder->Insert();
		$this->Log("Folder [".$name."] inserted with id [".$rs."]");
		return true;
	}
	public function CreateFile(string $location, string $name) {
		$this->Log("File [".$name."] found at [".$location."]");
		if($this->control->CheckIfFileExists($this->keyId, $location, $name)) return true;
		$file = new File();
		$file->keyFolder = $this->crawl->GetFolderKey();
		$file->name = $name;
		$file->location = $location;
		$file->apikey_id = $this->keyId;
		$file->GetFileData();
		$rs = $file->Insert();
		$this->Log("File [".$name."] inserted with id [".$rs."]");
		return true;
	}

	public function Start() {
		if(!$this->crawl->IsWaiting()) {
			return false;
		}
		$this->startTime = now();
		$this->crawl->executed_at = $this->startTime;
		$this->crawl->status = EnumCrawlStatus::PROCESSING->value;
		$this->crawl->result = " ";
		$this->crawl->Update();
		return true;
	}

	public function End() {
		$this->endTime = now();
		$this->crawl->status = EnumCrawlStatus::DONE->value;
		$this->crawl->result = $this->GenerateReport();
		$this->crawl->Update();
		return true;
	}

	public function LogError($error) {
		$this->crawl->status = EnumCrawlStatus::ERROR->value;
		$this->crawl->result = json_encode([
			"start_time" => $this->startTime,
			"status" => "error",
			"error" => $error,
		]);
		$this->crawl->Update();
	}

	public function GenerateReport() {
		return json_encode([
			"start_time" => $this->startTime,
			"end_time" => $this->endTime,
			"status" => "done",
			"log" => $this->log,
		]);
	}

	public function Log($data) {
		array_push($this->log, $data);
//		$append = "\t\t'".$data."',\n";
//		echo nl2br($append);
//		$this->control->AppendResult($append, $this->crawl->id);
	}

}
