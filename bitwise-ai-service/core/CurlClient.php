<?php
namespace Core;

class CurlClient
{
    public function __construct()
    {
        $this->config = include('config.php');
    }

    /**
     * Function used to execute the curl calls based on the given inputs
     * @param  [string] $url    [url to be executed]
     * @param  [string] $method [Curl request]
     * @param  [Array]  $params [Array of parameters]
     * @return [json]           [returns the response as JSON format]
     */
    public function exeCurl($url, $method, $params)
    {
      
        //End point validation
        if (! $this->endpointValidation($url)) {
            return 'Invalid endpoint';
        }

        //Parameter validation
        if (! $this->parameterValidation($params)) {
            return "Invalid input format";
        }

        if(true){
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config['base_url'].$url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_ENCODING, "");
            curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            
            if (!is_NULL($params)) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            }
            
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("cache-control:no-cache","content-type:application/json;charset=UTF-8"));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                return $response;
            }
        }
    }

    /**
     * Function is used to validate the given endpoints is valid or not.
     * @param  [string] $endpoint [Endpoint to be validated]
     * @return [bool]             [return true if it is valid, false otherwise]
     */
    private function endpointValidation($endpoint)
    {
        $endpoints = array(
                    'conceptMastery/R/get.cm.class/print',
                    'course.retention/R/get.retention.score/print'
                    );

        return (in_array($endpoint, $endpoints)) ? true : false;
    }

    /**
     * Function is used to validate the given parameters is valid or not.
     * @param  [string] $endpoint [requested endpoint]
     * @return [bool]            [return true if it is valid, false otherwise]
     */
    private function parameterValidation($params)
    {
        $inputs = json_decode($params);
        return ( is_object($inputs))  ? true : false;
    }

    /**
     * Function is used to validate the datatype of the given parameters.
     * @param  [string] $endpoint [requested endpoint]
     * @param  [array] $endpoint [Array of parameters to be validated]
     * @return [bool]            [return true if it is valid, false otherwise]
     */
    private function dataTypeValidation($endpoint, $inputs)
    {
        $message = '';
        $status = true;

        if ($endpoint==='conceptMastery/R/get.cm.class/print') {
            $params = array('anon_student_id',
                            'subject',
                            'concept');

            foreach ($params as $param) {
                if (!is_string($inputs->$param)) {
                    $message .= "<strong>$param</strong> should be string<br/>";
                    $status = false;
                }
            }
        }

        if($endpoint==='course.retention/R/get.retention.score/print') {
            $params = array("anon_student_id",
                            "course_id",
                            "elapsed_da",
                            "cumulative_session_time_sec",
                            "immediate_prev_att",
                            );
        }

        return array('status' => $status, 'message' => $message);
    }
}
