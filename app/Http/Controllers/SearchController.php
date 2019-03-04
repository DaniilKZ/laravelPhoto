<?php
namespace App\Http\Controllers;
use Validator;

use App\Search;
use Illuminate\Http\Request;

class SearchController extends Controller{
	public function index(){
		return view('welcome');
	}
	public function search(Request $request){
		$response = null; 
		$err = null; 

		$validator = Validator::make($request->all(), [
            'searchvalue' => 'required',
        ]);
        
        if ($validator->fails()) {
        	$err = "Ошибка, не все поля заполнены корректно!";  
        }else{  
			if($request->ajax()){

		        $validator = Validator::make($request->all(), [
		            'searchvalue' => 'required'
		        ]);
		        if (!$validator->fails()) { 
		            $database = new Search(); 

		            $database->searchvalue = $request->searchvalue;   
		            $database->save();
		        } 

					$curl = curl_init(); 
	 				$searchvalue= $request->input('searchvalue'); 
	 				$more = $request->input('more');  

	 				$more = (!empty($more)) ? $more : "1" ;
	 
	 					curl_setopt_array($curl, array( 
	 						CURLOPT_URL => "https://api.unsplash.com/search/photos?query=".$searchvalue."&page=".$more."&client_id=43103e0be850a282e8a573e651edd0ede075c963a1903e98d60b65aa5f917bca",
	 						CURLOPT_RETURNTRANSFER => true,
	 						CURLOPT_ENCODING => "",
	 						CURLOPT_MAXREDIRS => 10,
	 						CURLOPT_TIMEOUT => 30,
	 						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	 						CURLOPT_CUSTOMREQUEST => "GET"
	 					)); 
	 					$json = json_decode(curl_exec($curl), true);  
	 				
	 					$err = curl_error($curl); 
	 					curl_close($curl); 

	 					if ($err) {
	 						$err =  "cURL Error #:" . $err;
	 					} else {
	 						$response = $json['results'];
	 					}  
	 					 
			}else{ 
					$err = "Не верный завпрос!";
			} 
        }        
			return Response( json_encode( array('response' => $response, 'error' => $err) ) );  
	}

 
 


}

