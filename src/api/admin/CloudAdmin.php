<?php

namespace MagratheaCloud;

include("api.php");
use Magrathea2\Admin\Admin;
use Magrathea2\Tests\TestsManager;
use Magrathea2\Admin\AdminMenu;
use Magrathea2\Admin\Features\ApiExplorer\ApiExplorer;
use Magrathea2\Admin\Features\AppConfig\AdminFeatureAppConfig;

use MagratheaCloud\Apikey\ApikeyAdmin;
use MagratheaCloud\Crawl\CrawlAdmin;
use MagratheaCloud\File\FileAdmin;
use MagratheaCloud\Folder\FolderAdmin;
use MagratheaCloud\Sharekey\SharekeyAdmin;
use MagratheaCloud\MagratheaCloudApi;

class CloudAdmin extends Admin implements \Magrathea2\Admin\iAdmin {

	private $features = [];

	public function Initialize() {
		$this->SetTitle("Magrathea Cloud Admin");
		$this->SetPrimaryColor("#779");
		$this->SetLogo("images/logo.png");
		$this->AddTests();
	}

	public function AddTests(): Admin {
		$folder = realpath(__DIR__."/../tests/");
		TestsManager::Instance()->AddTest([$folder], "Cloud Helper Tests");
		return parent::AddTests();
	}

	public function Auth($user): bool {
		return !empty($user->id);
	}

	public function SetFeatures() {
		parent::SetFeatures();
		$this->AddAppConfiguration();
		$this->LoadApi();
		$this->LoadFeatures();
	}

	public function LoadApi() {
		$api = new MagratheaCloudApi();
		$apiFeature = new ApiExplorer();
		$apiFeature->SetApi($api);
		$this->features["api"] = $apiFeature;
		$this->AddFeature($apiFeature);
	}

	public function AddAppConfiguration() {
		$appConfig = new AdminFeatureAppConfig(true);
		$appConfig->featureName = "Settings";
		$appConfig->featureId = "AppConfigAppOnly";
		$this->features["appconfig"] = $appConfig;
		$this->AddFeature($appConfig);
	}

	public function LoadFeatures(){
		$this->features["apikey"] = new ApikeyAdmin();
		$this->features["file"] = new FileAdmin();
		$this->features["folder"] = new FolderAdmin();
		$this->features["sharekey"] = new SharekeyAdmin();
		$this->features["crawl"] = new CrawlAdmin();
		$this->AddFeaturesArray($this->features);
	}

	public function BuildMenu(): AdminMenu {
		$menu = new AdminMenu();
		$menu
		->Add($this->features["appconfig"]->GetMenuItem())

		->Add($menu->CreateTitle("Api"))
		->Add($this->features["api"]->GetMenuItem())

		->Add($menu->CreateTitle("Features"))
		->Add($this->features["apikey"]->GetMenuItem())
		->Add($this->features["file"]->GetMenuItem())
		->Add($this->features["folder"]->GetMenuItem())
		->Add($this->features["sharekey"]->GetMenuItem())
		->Add($this->features["crawl"]->GetMenuItem())

		->Add($menu->CreateSpace())
		->Add($menu->CreateSpace())

		->Add($menu->CreateTitle("Magrathea"));
		$this->AddMagratheaMenu($menu);

		$menu->Add($menu->GetLogoutMenuItem());
		return $menu;
	}

}

