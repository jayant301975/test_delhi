<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Yajra\DataTables\Facades\DataTables as DataTables;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Cache;
use Exception;

class UserController extends Controller
{
    
	public function index(Request $request)
{
    try {
        $gender = $request->query('gender'); 
        $results = $request->query('results', 50);
        $cacheKey = "users_" . ($gender ? strtolower($gender) : 'all') . "_$results";

       $users = Cache::remember($cacheKey, 60, function () use ($gender, $results) {
			$client = new Client();

			$query = ['results' => $results];
			if ($gender) {
				$query['gender'] = $gender;
			}

			$response = $client->request('GET', 'https://randomuser.me/api/', [
				'query' => $query
			]);

			$userData = json_decode($response->getBody(), true);
			return $userData['results'];
		});

        if ($request->ajax()) {
            return datatables()->of($users)
                ->addIndexColumn()
                ->addColumn('name', fn($row) => $row['name']['first'] . ' ' . $row['name']['last'])
                ->addColumn('email', fn($row) => $row['email'])
                ->addColumn('gender', fn($row) => ucfirst($row['gender']))
                ->addColumn('nationality', fn($row) => $row['location']['country'])
                ->make(true);
        }

        return view('welcome');

    } catch (Exception $e) {
        \Log::error('User API Error: ' . $e->getMessage());

        if ($request->ajax()) {
            return response()->json(['error' => 'Failed to fetch users.'], 500);
        }

        return redirect()->back()->with('error', 'Failed to fetch user data.');
    }
}

	
	

	public function exportcsv(Request $request)
	{
		 $gender = $request->query('gender');

		$queryParams = ['results' => 50];

		
		if (in_array($gender, ['male', 'female'])) {
			$queryParams['gender'] = $gender;
		}
		
		$userClient = new Client();
		$res=$userClient->request('GET','https://randomuser.me/api/', ['query' => $queryParams]);
        $userData=json_decode($res->getBody(),true);
		$data = $userData['results']; 
		$headers = ['Name', 'Email', 'Gender', 'Country'];


        $callback = function () use ($data, $headers) {
            $csv = Writer::createFromPath('php://output', 'w');
            $csv->insertOne($headers);

            foreach ($data as $row) {
            $name = $row['name']['first'] . ' ' . $row['name']['last'];
            $email = $row['email'];
            $gender = $row['gender'];
            $country = $row['location']['country'];
			$csv->insertOne([$name, $email, $gender, $country]);

            }
        };

        $response = new StreamedResponse();
        $response->setCallback($callback);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="data.csv"');

        return $response;
		
		
	}
	
	
	
	
	
	
	
}
