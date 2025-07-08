<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Yajra\DataTables\Facades\DataTables as DataTables;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;
class UserController extends Controller
{
    
	public function index(Request $request)
	{
		$userClient = new Client();
		$res=$userClient->request('GET','https://randomuser.me/api/', ['query' => ['results' => 50]]);
        $userData=json_decode($res->getBody(),true);
		$data = $userData['results']; 
        if ($request->ajax()) 
		{
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row['name']['first'] . ' ' . $row['name']['last'];
            })
            ->addColumn('email', function ($row) {
                return $row['email'];
            })
            ->addColumn('gender', function ($row) {
                return $row['gender'];
            })
            ->addColumn('nationality', function ($row) {
                return $row['location']['country'];
            })
           ->make(true);

		}			
       return view('welcome') ;  		
		
	}
	
	
	
	public function exportcsv()
	{
		$userClient = new Client();
		$res=$userClient->request('GET','https://randomuser.me/api/', ['query' => ['results' => 50]]);
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
