<?php

$data = array( 
	2,
	3,
	20,
	30,
	25,
	35,
	45, 
	15,
	10,
	5,
	7,9,8,90,56,78,87,65,78,89,67,79,67,68,69,69,69,69
);
$data=array(1,2,3,4,5,6,7,8,9);
echo "<pre>";
echo "Data:\n\n";
print_r($data);

print_r(kMeans($data, 3));

function initialiseCentroids(array $data, $k) {
	$dimensions = count($data[0]);
	$centroids = array();
	$dimmax = $data[0];
	$dimmin = $data[0]; 

	for($i = 0; $i < $k; $i++) {
		
		$centroids[$i] =initialiseCentroid($data);
	}
	return $centroids;
}

function initialiseCentroid($data) {
		$centroid = $data[rand(0, count($data)-1)];
		return $centroid;
}

function kMeans($data, $k) {
	$centroids = initialiseCentroids($data, $k);
	$mapping = array();

	while(true) {
		
		echo "\n\n\ngenerated random centroids:\n\n";
		print_r($centroids);
		$new_mapping = assignCentroids($data, $centroids);
		echo "\n\n\nmapping:\n\n";
		print_r($new_mapping);
		$changed = false;
		foreach($new_mapping as $documentID => $centroidID) {
			if(!isset($mapping[$documentID]) || $centroidID != $mapping[$documentID]) {
				$mapping = $new_mapping;
				$changed = true;
				break;
			}
		}
		if(!$changed){
			return formatResults($mapping, $data, $centroids); 
		}
		$centroids  = updateCentroids($mapping, $data, $k); 
	}
}

function formatResults($mapping, $data, $centroids) {
	$result  = array();
	$result['centroids'] = $centroids;
	foreach($mapping as $documentID => $centroidID) {
		$result[$centroidID][] = $data[$documentID];
	}
	echo "\n\n\n ========================Final results======================================<b>\n\n";
	return $result;
}

function assignCentroids($data, $centroids) {
	$mapping = array();

	foreach($data as $documentID => $document) {
		$minDist = 100;
		$minCentroid = null;
		foreach($centroids as $centroidID => $centroid) {
			$dist = 0;
			
				$dist = abs($centroid - $document);
		
			if($dist < $minDist) {
				$minDist = $dist;
				$minCentroid = $centroidID;
			}
		}
		$mapping[$documentID] = $minCentroid;
	}

	return $mapping;
}

function updateCentroids($mapping, $data, $k) {
	$centroids = array();
	$counts = array_count_values($mapping);

	foreach($mapping as $documentID => $centroidID) {
	
			if(!isset($centroids[$centroidID])) {
				$centroids[$centroidID] = 0;
			}
			
			$centroids[$centroidID] += $data[$documentID]/$counts[$centroidID]; 
		
	}
	
	if(count($centroids) < $k) {
		$centroids = array_merge($centroids, initialiseCentroids($data, $k - count($centroids)));
	}
   
   foreach($centroids as $centID=>$val)
	{
	$temp=null;
   foreach($mapping as $documentID => $centroidID) {

		if($centroidID==$centID)
		{
			if($temp== null || abs($val - $temp) > abs($data[$documentID] - $val)) {

			$temp= $data[$documentID];
		}
	  }
	}
	$centroids[$centID]=$temp;
   }
	$centroids=array_map(function($x){return round($x);},$centroids);
	echo "\n\n\n new centroids\n\n";
	print_r($centroids);


	return $centroids;
}



