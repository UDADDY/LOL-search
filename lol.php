<?PHP
	$ch = curl_init();
	$apiKey = 'RGAPI-604a91e1-40ee-426b-b105-52c655d59c86';
	$searchName = 'UDADDY';

	//
	// 사용자 정보
	$address = 'https://kr.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $searchName . '?api_key=' . $apiKey;
	curl_setopt($ch, CURLOPT_URL, $address);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);

	$data = json_decode($response, true);
	$pretty = json_encode($data, JSON_PRETTY_PRINT);

	echo "<pre>" . $pretty . "</pre>";

	// 정보들
	$name = $data['name'];
	$profileIconId = $data['profileIconId'];
	$summonerLevel = $data['summonerLevel'];
	// 사용자 암호화된 ID
	$encryptedSummonerId = $data['id'];
	$puuid = $data['puuid'];


	//
	// 랭크 정보
	$address = 'https://kr.api.riotgames.com/lol/league/v4/entries/by-summoner/' . $encryptedSummonerId . '?api_key=' . $apiKey;
	curl_setopt($ch, CURLOPT_URL, $address);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);
	$data = json_decode($response, true);
	$pretty = json_encode($data, JSON_PRETTY_PRINT);
	echo "<pre>" . $pretty . "</pre>";

	// 정보들
	$soloRank;
	$teamRank;

	foreach($data as $queueType){
		// 솔로 랭크
		if($queueType['queueType'] == "RANKED_SOLO_5x5"){
			$soloRank['tier'] = $queueType['tier'];
			$soloRank['rank'] = $queueType['rank'];
			$soloRank['leaguePoints'] = $queueType['leaguePoints'];
			$soloRank['wins'] = $queueType['wins'];
			$soloRank['losses'] = $queueType['losses'];
			$soloRank['winRate'] = round(($queueType['wins'] / ($queueType['wins'] + $queueType['losses'])) * 100);
		} 
		// 자유 랭크
		else if($queueType['queueType'] == "RANKED_FLEX_SR"){
			$teamRank['tier'] = $queueType['tier'];
			$teamRank['rank'] = $queueType['rank'];
			$teamRank['leaguePoints'] = $queueType['leaguePoints'];
			$teamRank['wins'] = $queueType['wins'];
			$teamRank['losses'] = $queueType['losses'];
			$teamRank['winRate'] = round(($queueType['wins'] / ($queueType['wins'] + $queueType['losses'])) * 100);
		}
	}

	print_r($soloRank);
	echo "<br>";
	print_r($teamRank);

	//
	// 대전 기록 정보
	$address = 'https://asia.api.riotgames.com/lol/match/v5/matches/by-puuid/' . $puuid . '/ids?start=0&count=20&api_key=' . $apiKey;
	curl_setopt($ch, CURLOPT_URL, $address);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);
	$data = json_decode($response, true);
	$pretty = json_encode($data, JSON_PRETTY_PRINT);
	echo "<pre>" . $pretty . "</pre>";

	
	
	curl_close($ch);
?>