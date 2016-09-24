<?php
/**
 * IMDBv API wrapper
 */
namespace IMVDb;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Exception\ClientErrorResponseException;

class API implements APIInterface
{
	protected $APIKey = '';
	protected $APIUrl = 'https://imvdb.com/api/v1/';

	public function __construct($APIKey='')
	{
		$this->APIKey = $APIKey;
	}

	/**
	 * Search videos
	 *
	 * @param string $query
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array
	 */ 
	public function searchVideos($query='', $start=1, $limit=10)
	{
		// Verify input
		if($query == '') return false;
		// Query
		$searchResult = $this->request('search/videos', 
			array(
				'q'=>$query,
				'per_page'=>$limit,
				'page'=>$start,
			)
		);
		return $searchResult;
	}

	/**
	 * Search entities
	 *
	 * @param string $query
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array
	 */ 
	public function searchEntities($query='', $start=1, $limit=10)
	{
		// Verify input
		if($query == '') return false;
		// Query
		$searchResult = $this->request('search/entities', 
			array(
				'q'=>$query,
				'per_page'=>$limit,
				'page'=>$start,
			)
		);
		return $searchResult;
	}

	/**
	 * Retreive data from the API on individual music videos
	 *
	 * @param int $id
	 * @param array $includes - more video data 'sources', 'credits', 'featured', 'popularity', 'bts', 'countries'
	 *
	 * @return array
	 */ 
	public function video($id=0, $includes=array('sources'))
	{
		// Verify input
		if($id == 0) return false;
		// Query
		$videoResult = $this->request('video/'.$id, array('include'=>implode(',', $includes)));
		return $videoResult;
	}

	/**
	 * Retreive basic information on an entity as well as information like credits and associated videos.
	 *
	 * @param int $id
	 * @param array $includes - more entity data 'credits', 'credit_summary', 'artist_videos', 'featured_videos'
	 *
	 * @return array
	 */
	public function entity($id=0, $includes=array(''))
	{
		// Verify input
		if($id == 0) return false;
		// Query
		$entityResult = $this->request('entity/'.$id, array('include'=>implode(',', $includes)));
		return $entityResult;
	}

	/**
	 * Request IMVDb API
	 * 
	 * @param string $path
	 * @param array $parameters
	 *
	 * @return array
	 */
	private function request($path, $parameters=array())
	{
		$client = new \GuzzleHttp\Client();
		try {
			$res = $client->request('GET', $this->APIUrl.$path.'?'.http_build_query($parameters), array('Accept: application/json'));
		} catch (RequestException $e) {
			return false;
		}
		if($res->getStatusCode() != '200') return false;
		return json_decode($res->getBody()->getContents(), true);
	}
}