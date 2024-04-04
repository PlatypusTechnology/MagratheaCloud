<?php

namespace MagratheaCloud\Crawl;
use Magrathea2\Magrathea_Enum;

enum EnumCrawlStatus: string {
	use Magrathea_Enum;
	case WAITING = "Waiting";
	case PROCESSING = "Processing";
	case DONE = "Done";
	case ERROR = "Error";
}
