<?php

namespace MagratheaCloud;
use MagratheaCloud\Apikey\ApikeyAdmin;
use MagratheaCloud\File\FileAdmin;
use MagratheaCloud\Sharekey\SharekeyAdmin;

include("api.php");
use Magrathea2\Admin\AdminMenu;
use Magrathea2\Admin\Features\ApiExplorer\ApiExplorer;
use Magrathea2\Admin\Features\AppConfig\AdminFeatureAppConfig;
use MagratheaCloud\MagratheaCloudApi;

class CloudAdmin extends \Magrathea2\Admin\Admin implements \Magrathea2\Admin\iAdmin {

	private $features = [];

	public function Initialize() {
		$this->SetTitle("Magrathea Images Admin");
		$this->SetPrimaryColor("#910e04");
		$this->LoadApi();
		$this->LoadFeatures();
	}

	public function Auth($user): bool {
		return !empty($user->id);
	}

	public function LoadApi() {
		$api = new MagratheaCloudApi();
		$apiFeature = new ApiExplorer();
		$apiFeature->SetApi($api);
		$this->features["api"] = $apiFeature;
		$this->AddFeature($apiFeature);
	}

	public function LoadFeatures(){
		$this->features["appconfig"] = new AdminFeatureAppConfig(true);
		$this->features["apikey"] = new ApikeyAdmin();
		$this->features["file"] = new FileAdmin();
		$this->features["sharekey"] = new SharekeyAdmin();
		$this->AddFeaturesArray($this->features);
	}

	public function BuildMenu(): AdminMenu {
		$menu = new AdminMenu();
		$menu
		->Add($this->features["appconfig"]->GetMenuItem())

		->Add($menu->CreateTitle("Features"))
		->Add($this->features["apikey"]->GetMenuItem())
		->Add($this->features["file"]->GetMenuItem())
		->Add($this->features["sharekey"]->GetMenuItem())

		->Add($menu->CreateTitle("Api"))
		->Add($this->features["api"]->GetMenuItem())

		->Add($menu->CreateSpace())

		->Add($menu->CreateTitle("Magrathea"))
		->Add(["title" => "Magrathea >>", "link" => "/magrathea.php"])

		->Add($menu->GetLogoutMenuItem());
		return $menu;
	}

}

